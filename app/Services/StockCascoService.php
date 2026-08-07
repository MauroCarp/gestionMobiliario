<?php

namespace App\Services;

use App\Models\LoteProcesoExterno;
use App\Models\Mobiliario;
use App\Models\PlantillaFlujoExterno;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StockCascoService
{
    public const CATEGORIA_SILLAS = 'Sillas';

    public const ESTADOS_ACTIVOS = [
        'confirmado',
        'pagado',
        'entregado_parcial',
    ];

    public const LOTES_ABIERTOS = [
        'pendiente',
        'en_proceso',
    ];

    public function plantillaCascoActiva(int $mobiliarioId): ?PlantillaFlujoExterno
    {
        return PlantillaFlujoExterno::query()
            ->where('entidad_tipo', 'mobiliario')
            ->where('entidad_id', $mobiliarioId)
            ->where('activo', true)
            ->with('mobiliario.categoria')
            ->first();
    }

    public function esMobiliarioCascoSilla(Mobiliario|int $mobiliario): bool
    {
        $model = $mobiliario instanceof Mobiliario
            ? $mobiliario
            : Mobiliario::with('categoria')->find($mobiliario);

        if (! $model) {
            return false;
        }

        $model->loadMissing('categoria');

        return str_contains(
            mb_strtolower($model->categoria?->nombre ?? ''),
            'silla',
        );
    }

    public function demandaActiva(int $mobiliarioId): int
    {
        return (int) PresupuestoItem::query()
            ->where('mobiliario_id', $mobiliarioId)
            ->whereNull('finalizado_at')
            ->whereHas('presupuesto', fn ($q) => $q->whereIn('estado', self::ESTADOS_ACTIVOS))
            ->sum('cantidad');
    }

    public function cantidadEnLotesAbiertos(int $mobiliarioId, ?int $plantillaId = null): int
    {
        $query = LoteProcesoExterno::query()
            ->where('entidad_tipo', 'mobiliario')
            ->where('entidad_id', $mobiliarioId)
            ->whereIn('estado', self::LOTES_ABIERTOS);

        if ($plantillaId) {
            $query->where('plantilla_id', $plantillaId);
        }

        return (int) $query->sum('cantidad');
    }

    /**
     * Cantidad de cascos a fabricar:
     * max(0, demanda_activa - stock_casco - lotes_abiertos)
     */
    public function calcularCantidadAFabricar(int $mobiliarioId): int
    {
        $plantilla = $this->plantillaCascoActiva($mobiliarioId);

        if (! $plantilla || ! $plantilla->esCascoSilla()) {
            return 0;
        }

        $demanda = $this->demandaActiva($mobiliarioId);
        $cobertura = (int) $plantilla->stock_casco
            + $this->cantidadEnLotesAbiertos($mobiliarioId, $plantilla->id);

        return max(0, $demanda - $cobertura);
    }

    /**
     * @return array<int, float> [insumo_id => cantidad]
     */
    public function demandaInsumosCascoParaLote(Mobiliario $mobiliario, int $cantidadLote): array
    {
        if ($cantidadLote <= 0) {
            return [];
        }

        $mobiliario->loadMissing('composicionTecnica');

        $demanda = [];

        foreach ($mobiliario->composicionTecnica as $comp) {
            if (! $comp->es_componente_casco) {
                continue;
            }

            $demanda[$comp->insumo_id] = ($demanda[$comp->insumo_id] ?? 0)
                + ((float) $comp->cantidad * $cantidadLote);
        }

        return $demanda;
    }

    /**
     * Demanda de insumos casco para un presupuesto.
     * Si ya existe un lote abierto originado por el presupuesto, usa esa cantidad;
     * si no, usa el faltante global actual (calcularCantidadAFabricar).
     *
     * @return array<int, float>
     */
    public function demandaInsumosCascoPresupuesto(Presupuesto $presupuesto): array
    {
        $presupuesto->loadMissing(['items.mobiliario.composicionTecnica', 'items.mobiliario.categoria']);

        $demanda = [];
        $mobiliariosProcesados = [];

        foreach ($presupuesto->items as $item) {
            if (! $item->mobiliario_id || isset($mobiliariosProcesados[$item->mobiliario_id])) {
                continue;
            }

            if ($item->estaFinalizado()) {
                continue;
            }

            $mobiliariosProcesados[$item->mobiliario_id] = true;

            $plantilla = $this->plantillaCascoActiva($item->mobiliario_id);

            if (! $plantilla?->esCascoSilla()) {
                continue;
            }

            $loteExistente = LoteProcesoExterno::query()
                ->where('entidad_tipo', 'mobiliario')
                ->where('entidad_id', $item->mobiliario_id)
                ->where('origen_id', $presupuesto->id)
                ->whereIn('estado', self::LOTES_ABIERTOS)
                ->first();

            $cantidadLote = $loteExistente
                ? (int) $loteExistente->cantidad
                : $this->calcularCantidadAFabricar($item->mobiliario_id);

            foreach ($this->demandaInsumosCascoParaLote($item->mobiliario, $cantidadLote) as $insumoId => $cantidad) {
                $demanda[$insumoId] = ($demanda[$insumoId] ?? 0) + $cantidad;
            }
        }

        return $demanda;
    }

    /**
     * Snapshot de lectura para simulación / diagnóstico.
     *
     * @return array<string, mixed>
     */
    public function analizar(int $mobiliarioId): array
    {
        $mobiliario = Mobiliario::with(['categoria', 'composicionTecnica.insumo'])->find($mobiliarioId);

        if (! $mobiliario) {
            return [
                'ok' => false,
                'error' => "No se encontró el mobiliario #{$mobiliarioId}.",
            ];
        }

        $esSilla = $this->esMobiliarioCascoSilla($mobiliario);
        $plantilla = $this->plantillaCascoActiva($mobiliarioId);

        $avisos = [];

        if (! $esSilla) {
            $avisos[] = 'El mobiliario no pertenece a una categoría de sillas.';
        }

        if (! $plantilla) {
            $avisos[] = 'No hay plantilla de flujo externo activa para este mobiliario.';
        } elseif (! $plantilla->esCascoSilla()) {
            $avisos[] = 'La plantilla activa no se reconoce como casco de silla.';
        }

        $stockCasco = (int) ($plantilla?->stock_casco ?? 0);
        $demanda = $this->demandaActiva($mobiliarioId);
        $enLotes = $plantilla
            ? $this->cantidadEnLotesAbiertos($mobiliarioId, $plantilla->id)
            : 0;
        $cobertura = $stockCasco + $enLotes;
        $desdeStock = min($demanda, $stockCasco);
        $aFabricar = max(0, $demanda - $cobertura);

        $presupuestos = $this->presupuestosConMobiliario($mobiliarioId);
        $lotesAbiertos = $this->lotesAbiertosDetalle($mobiliarioId, $plantilla?->id);
        $insumosCasco = $this->insumosCascoDetalle($mobiliario, $aFabricar);

        return [
            'ok' => true,
            'avisos' => $avisos,
            'mobiliario' => [
                'id' => $mobiliario->id,
                'codigo' => $mobiliario->codigo_interno,
                'nombre' => $mobiliario->nombre,
                'categoria' => $mobiliario->categoria?->nombre,
            ],
            'plantilla' => $plantilla ? [
                'id' => $plantilla->id,
                'nombre' => $plantilla->nombre,
                'activo' => $plantilla->activo,
            ] : null,
            'stock_casco' => $stockCasco,
            'demanda_activa' => $demanda,
            'en_lotes_abiertos' => $enLotes,
            'cobertura' => $cobertura,
            'cantidad_desde_stock' => $desdeStock,
            'cantidad_a_fabricar' => $aFabricar,
            'lote_a_crear' => $aFabricar,
            'presupuestos' => $presupuestos,
            'lotes_abiertos' => $lotesAbiertos,
            'insumos_casco' => $insumosCasco,
        ];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function presupuestosConMobiliario(int $mobiliarioId): Collection
    {
        $items = PresupuestoItem::query()
            ->where('mobiliario_id', $mobiliarioId)
            ->whereHas('presupuesto', fn ($q) => $q->whereIn('estado', self::ESTADOS_ACTIVOS))
            ->with('presupuesto')
            ->get();

        return $items
            ->groupBy('presupuesto_id')
            ->map(function (Collection $grupo) {
                /** @var PresupuestoItem $primero */
                $primero = $grupo->first();
                $presupuesto = $primero->presupuesto;

                return [
                    'presupuesto_id' => $presupuesto?->id,
                    'codigo' => $presupuesto?->codigo,
                    'estado' => $presupuesto?->estado,
                    'cantidad_total' => (int) $grupo->sum('cantidad'),
                    'cantidad_activa' => (int) $grupo->whereNull('finalizado_at')->sum('cantidad'),
                    'items' => $grupo->map(fn (PresupuestoItem $item) => [
                        'item_id' => $item->id,
                        'cantidad' => (int) $item->cantidad,
                        'finalizado' => $item->estaFinalizado(),
                    ])->values()->all(),
                ];
            })
            ->values();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function lotesAbiertosDetalle(int $mobiliarioId, ?int $plantillaId = null): Collection
    {
        $query = LoteProcesoExterno::query()
            ->where('entidad_tipo', 'mobiliario')
            ->where('entidad_id', $mobiliarioId)
            ->whereIn('estado', self::LOTES_ABIERTOS);

        if ($plantillaId) {
            $query->where('plantilla_id', $plantillaId);
        }

        return $query->get()->map(function (LoteProcesoExterno $lote) {
            $origenCodigo = null;

            if ($lote->origen_id) {
                $origenCodigo = Presupuesto::find($lote->origen_id)?->codigo;
            }

            return [
                'id' => $lote->id,
                'codigo' => $lote->codigo,
                'estado' => $lote->estado,
                'cantidad' => (int) $lote->cantidad,
                'origen_id' => $lote->origen_id,
                'origen_codigo' => $origenCodigo,
            ];
        });
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function insumosCascoDetalle(Mobiliario $mobiliario, int $cantidadLote): Collection
    {
        $mobiliario->loadMissing('composicionTecnica.insumo');

        return $mobiliario->composicionTecnica
            ->filter(fn ($comp) => (bool) $comp->es_componente_casco)
            ->map(function ($comp) use ($cantidadLote) {
                $qtyUnitaria = (float) $comp->cantidad;

                return [
                    'insumo_id' => $comp->insumo_id,
                    'insumo' => $comp->insumo?->nombre ?? '—',
                    'codigo' => $comp->insumo?->codigo ?? '—',
                    'qty_unitaria' => $qtyUnitaria,
                    'total' => $qtyUnitaria * $cantidadLote,
                    'stock_actual' => (float) ($comp->insumo?->stock_actual ?? 0),
                ];
            })
            ->values();
    }

    /**
     * IDs de mobiliarios con plantilla casco activa.
     *
     * @return Collection<int, int>
     */
    public function mobiliariosCascoActivos(): Collection
    {
        return PlantillaFlujoExterno::query()
            ->cascosSilla()
            ->where('activo', true)
            ->pluck('entidad_id')
            ->unique()
            ->values()
            ->map(fn ($id) => (int) $id);
    }

    public function elegirPresupuestoOrigen(int $mobiliarioId): ?Presupuesto
    {
        $grupos = PresupuestoItem::query()
            ->where('mobiliario_id', $mobiliarioId)
            ->whereNull('finalizado_at')
            ->whereHas('presupuesto', fn ($q) => $q->whereIn('estado', self::ESTADOS_ACTIVOS))
            ->with('presupuesto')
            ->get()
            ->groupBy('presupuesto_id');

        if ($grupos->isEmpty()) {
            return null;
        }

        $candidatos = [];

        foreach ($grupos as $presupuestoId => $items) {
            /** @var PresupuestoItem $primero */
            $primero = $items->first();
            $presupuesto = $primero->presupuesto;

            if (! $presupuesto) {
                continue;
            }

            $tieneLoteAbierto = LoteProcesoExterno::query()
                ->where('entidad_tipo', 'mobiliario')
                ->where('entidad_id', $mobiliarioId)
                ->where('origen_id', $presupuestoId)
                ->whereIn('estado', self::LOTES_ABIERTOS)
                ->exists();

            $candidatos[] = [
                'presupuesto'      => $presupuesto,
                'cantidad_activa'  => (int) $items->sum('cantidad'),
                'sin_lote_abierto' => ! $tieneLoteAbierto,
            ];
        }

        if ($candidatos === []) {
            return null;
        }

        foreach ($candidatos as $c) {
            if ($c['sin_lote_abierto']) {
                return $c['presupuesto'];
            }
        }

        usort($candidatos, fn ($a, $b) => $b['cantidad_activa'] <=> $a['cantidad_activa']);

        return $candidatos[0]['presupuesto'];
    }

    /**
     * @return array<string, mixed>
     */
    public function sincronizarMobiliario(int $mobiliarioId, bool $dryRun = false): array
    {
        $analisis = $this->analizar($mobiliarioId);
        $errores = [];
        $loteCreado = null;
        $presupuestosRecalculados = [];

        if (! ($analisis['ok'] ?? false)) {
            return [
                'ok'                       => false,
                'mobiliario_id'            => $mobiliarioId,
                'analisis'                 => $analisis,
                'lote_creado'              => null,
                'presupuestos_recalculados'=> [],
                'errores'                  => [$analisis['error'] ?? 'Análisis fallido'],
            ];
        }

        $aFabricar = (int) ($analisis['cantidad_a_fabricar'] ?? 0);
        $presupuestoIds = collect($analisis['presupuestos'])
            ->pluck('presupuesto_id')
            ->filter()
            ->unique()
            ->values();

        if ($dryRun) {
            $presupuestoOrigen = $this->elegirPresupuestoOrigen($mobiliarioId);
            $codigosRecalcular = Presupuesto::query()
                ->whereIn('id', $presupuestoIds)
                ->pluck('codigo')
                ->all();

            return [
                'ok'                        => true,
                'dry_run'                   => true,
                'mobiliario_id'             => $mobiliarioId,
                'analisis'                  => $analisis,
                'lote_creado'               => null,
                'accion_lote'               => $aFabricar > 0 ? [
                    'cantidad'    => $aFabricar,
                    'presupuesto' => $presupuestoOrigen?->codigo,
                ] : null,
                'presupuestos_recalculados' => $codigosRecalcular,
                'errores'                   => $aFabricar > 0 && ! $presupuestoOrigen
                    ? ['Hay faltante pero no hay presupuesto activo para originar el lote.']
                    : [],
            ];
        }

        DB::transaction(function () use (
            $mobiliarioId,
            $aFabricar,
            $presupuestoIds,
            &$loteCreado,
            &$presupuestosRecalculados,
            &$errores,
        ) {
            /** @var StockReservaService $stockReserva */
            $stockReserva = app(StockReservaService::class);

            if ($aFabricar > 0) {
                $presupuesto = $this->elegirPresupuestoOrigen($mobiliarioId);

                if (! $presupuesto) {
                    $errores[] = 'Hay faltante pero no hay presupuesto activo para originar el lote.';
                } else {
                    $observaciones = sprintf(
                        'Sincronización cascos %s (%d uds)',
                        $presupuesto->codigo,
                        $aFabricar,
                    );

                    $loteCreado = $stockReserva->crearLoteCascoMobiliario(
                        $presupuesto,
                        $mobiliarioId,
                        $aFabricar,
                        $observaciones,
                    );

                    if (! $loteCreado) {
                        $errores[] = 'No se pudo crear el lote (sin plantilla activa).';
                    }
                }
            }

            foreach ($presupuestoIds as $presupuestoId) {
                $presupuesto = Presupuesto::find($presupuestoId);

                if (! $presupuesto) {
                    continue;
                }

                $stockReserva->recalcularInsumos($presupuesto);
                $presupuestosRecalculados[] = $presupuesto->codigo;
            }
        });

        return [
            'ok'                        => empty($errores),
            'mobiliario_id'             => $mobiliarioId,
            'analisis'                  => $analisis,
            'lote_creado'               => $loteCreado ? [
                'id'       => $loteCreado->id,
                'codigo'   => $loteCreado->codigo,
                'cantidad' => (int) $loteCreado->cantidad,
            ] : null,
            'presupuestos_recalculados' => $presupuestosRecalculados,
            'errores'                   => $errores,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function sincronizarTodos(bool $dryRun = false): array
    {
        $resultados = [];
        $totales = [
            'mobiliarios'           => 0,
            'lotes_creados'         => 0,
            'presupuestos_recalculados' => 0,
            'errores'               => 0,
        ];

        foreach ($this->mobiliariosCascoActivos() as $mobiliarioId) {
            $resultado = $this->sincronizarMobiliario($mobiliarioId, $dryRun);
            $resultados[] = $resultado;
            $totales['mobiliarios']++;

            if ($resultado['lote_creado'] ?? null) {
                $totales['lotes_creados']++;
            } elseif (($resultado['accion_lote'] ?? null) && $dryRun) {
                $totales['lotes_creados']++;
            }

            $totales['presupuestos_recalculados'] += count($resultado['presupuestos_recalculados'] ?? []);

            if (! empty($resultado['errores'])) {
                $totales['errores'] += count($resultado['errores']);
            }
        }

        return [
            'dry_run'    => $dryRun,
            'totales'    => $totales,
            'resultados' => $resultados,
        ];
    }
}
