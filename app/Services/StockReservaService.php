<?php

namespace App\Services;

use App\Models\Insumo;
use App\Models\LoteProcesoExterno;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraItem;
use App\Models\PlantillaFlujoExterno;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Models\ReservaStock;
use Illuminate\Support\Facades\DB;

/**
 * Gestiona el ciclo de vida del stock:
 *  - Reservar insumos al confirmar/pagar un presupuesto
 *  - Liberar reservas al cancelar/rechazar
 *  - Descontar stock real al marcar como pagado
 *  - Generar órdenes de compra automáticas
 */
class StockReservaService
{
    public function __construct(
        private readonly StockCascoService $stockCasco,
        private readonly ConfirmacionPresupuestoPlanificador $planificador,
    ) {}

    /**
     * Reserva el stock necesario para un presupuesto confirmado.
     * Idempotente: si ya existen reservas activas no las duplica.
     */
    public function reservar(Presupuesto $presupuesto): void
    {
        // Si ya tiene reservas activas, nada que hacer
        if ($presupuesto->reservasStock()->where('estado', 'activa')->exists()) {
            return;
        }

        $demanda = $this->calcularDemandaPresupuesto($presupuesto);

        DB::transaction(function () use ($presupuesto, $demanda) {
            foreach ($demanda as $insumoId => $cantidad) {
                ReservaStock::create([
                    'presupuesto_id'    => $presupuesto->id,
                    'insumo_id'         => $insumoId,
                    'cantidad_reservada' => $cantidad,
                    'estado'            => 'activa',
                ]);
            }
        });
    }

    /**
     * Libera todas las reservas activas de un presupuesto (cancelación / rechazo).
     */
    public function liberar(Presupuesto $presupuesto): void
    {
        $presupuesto->reservasStock()
            ->where('estado', 'activa')
            ->update(['estado' => 'liberada']);

        $this->cancelarLotesAbiertosDelPresupuesto($presupuesto);
    }

    /**
     * Cancela lotes externos abiertos originados por este presupuesto
     * para que dejen de contar en la cobertura de cascos.
     */
    public function cancelarLotesAbiertosDelPresupuesto(Presupuesto $presupuesto): void
    {
        LoteProcesoExterno::query()
            ->where('origen_id', $presupuesto->id)
            ->where('origen_tipo', 'manual')
            ->whereIn('estado', StockCascoService::LOTES_ABIERTOS)
            ->update(['estado' => 'cancelado']);
    }

    /**
     * Recalcula la demanda de insumos del presupuesto contra la composición
     * técnica actual y sincroniza las reservas activas.
     * Solo considera ítems sin finalizado_at (los finalizados ya consumieron stock).
     * No toca stock_actual ni órdenes de compra.
     *
     * @return array{creadas:int, actualizadas:int, liberadas:int}
     */
    public function recalcularInsumos(Presupuesto $presupuesto): array
    {
        $demanda = $this->calcularDemandaPendiente($presupuesto);

        return DB::transaction(function () use ($presupuesto, $demanda) {
            $reservasActivas = ReservaStock::where('presupuesto_id', $presupuesto->id)
                ->where('estado', 'activa')
                ->lockForUpdate()
                ->get()
                ->groupBy('insumo_id');

            $creadas = 0;
            $actualizadas = 0;
            $liberadas = 0;

            foreach ($demanda as $insumoId => $cantidad) {
                $cantidad = (float) $cantidad;

                if ($cantidad <= 0) {
                    continue;
                }

                $grupo = $reservasActivas->get($insumoId);

                if ($grupo === null || $grupo->isEmpty()) {
                    ReservaStock::create([
                        'presupuesto_id'     => $presupuesto->id,
                        'insumo_id'          => $insumoId,
                        'cantidad_reservada' => $cantidad,
                        'estado'             => 'activa',
                    ]);
                    $creadas++;

                    continue;
                }

                /** @var \Illuminate\Support\Collection<int, ReservaStock> $grupo */
                $principal = $grupo->first();

                foreach ($grupo->slice(1) as $extra) {
                    $extra->update(['estado' => 'liberada']);
                    $liberadas++;
                }

                if (abs((float) $principal->cantidad_reservada - $cantidad) > 0.01) {
                    $principal->update(['cantidad_reservada' => $cantidad]);
                    $actualizadas++;
                }
            }

            $insumosConDemanda = array_map(
                'intval',
                array_keys(array_filter(
                    $demanda,
                    fn ($c) => (float) $c > 0
                ))
            );

            foreach ($reservasActivas as $insumoId => $grupo) {
                if (in_array((int) $insumoId, $insumosConDemanda, true)) {
                    continue;
                }

                foreach ($grupo as $reserva) {
                    if ($reserva->estado === 'activa') {
                        $reserva->update(['estado' => 'liberada']);
                        $liberadas++;
                    }
                }
            }

            return [
                'creadas'      => $creadas,
                'actualizadas' => $actualizadas,
                'liberadas'    => $liberadas,
            ];
        });
    }

    /**
     * Descuenta el stock real y marca reservas como consumidas (presupuesto pagado).
     * @deprecated Usar finalizarItem() por línea de ítem.
     */
    public function consumir(Presupuesto $presupuesto): void
    {
        DB::transaction(function () use ($presupuesto) {
            $reservas = $presupuesto->reservasStock()
                ->where('estado', 'activa')
                ->with('insumo')
                ->get();

            foreach ($reservas as $reserva) {
                // Descontar stock_actual
                Insumo::where('id', $reserva->insumo_id)
                    ->decrement('stock_actual', $reserva->cantidad_reservada);

                $reserva->update(['estado' => 'consumida']);
            }
        });
    }

    /**
     * Confirma la finalización de una línea de ítem: descuenta stock real
     * y reduce la reserva activa (comprometido) del presupuesto.
     * Idempotente: si el ítem ya está finalizado, no hace nada.
     */
    public function finalizarItem(PresupuestoItem $item): void
    {
        if ($item->estaFinalizado()) {
            return;
        }

        // demandaInsumos() ya excluye es_componente_casco
        $demanda = $item->demandaInsumos();

        DB::transaction(function () use ($item, $demanda) {
            foreach ($demanda as $insumoId => $cantidad) {
                Insumo::where('id', $insumoId)
                    ->decrement('stock_actual', $cantidad);

                $this->reducirReservaActiva(
                    (int) $item->presupuesto_id,
                    (int) $insumoId,
                    (float) $cantidad,
                );
            }

            if ($item->mobiliario_id) {
                $plantilla = $this->stockCasco->plantillaCascoActiva((int) $item->mobiliario_id);

                if ($plantilla?->esCascoSilla()) {
                    $plantilla->decrement('stock_casco', (int) $item->cantidad);
                }
            }

            $item->update([
                'finalizado_at'    => now(),
                'finalizado_por'   => auth()->id(),
            ]);
        });
    }

    /**
     * Descuenta insumos de casco y reduce reservas al completar un lote externo.
     *
     * @param  array<int, float>  $demanda
     */
    public function consumirInsumosCascoDeLote(LoteProcesoExterno $lote, array $demanda): void
    {
        if (empty($demanda)) {
            return;
        }

        DB::transaction(function () use ($lote, $demanda) {
            foreach ($demanda as $insumoId => $cantidad) {
                Insumo::where('id', $insumoId)
                    ->decrement('stock_actual', $cantidad);

                if ($lote->origen_id) {
                    $this->reducirReservaActiva(
                        (int) $lote->origen_id,
                        (int) $insumoId,
                        (float) $cantidad,
                    );
                }
            }
        });
    }

    private function reducirReservaActiva(int $presupuestoId, int $insumoId, float $cantidad): void
    {
        $reserva = ReservaStock::where('presupuesto_id', $presupuestoId)
            ->where('insumo_id', $insumoId)
            ->where('estado', 'activa')
            ->lockForUpdate()
            ->first();

        if (! $reserva) {
            return;
        }

        $nuevaCantidad = max(0, (float) $reserva->cantidad_reservada - $cantidad);

        if ($nuevaCantidad <= 0) {
            $reserva->update([
                'cantidad_reservada' => 0,
                'estado'             => 'consumida',
            ]);
        } else {
            $reserva->update(['cantidad_reservada' => $nuevaCantidad]);
        }
    }

    /**
     * Al confirmar: planifica lotes/OC, reserva insumos y persiste el plan.
     *
     * @return array{lotes: list<LoteProcesoExterno>, ordenes: list<OrdenCompra>, plan: array<string, mixed>}
     */
    public function aplicarEfectosConfirmacion(Presupuesto $presupuesto): array
    {
        $plan = $this->simularConfirmacion($presupuesto);
        $this->reservar($presupuesto);
        $persistido = $this->persistirPlanConfirmacion($presupuesto, $plan);

        return [
            'lotes'   => $persistido['lotes'],
            'ordenes' => $persistido['ordenes'],
            'plan'    => $plan,
        ];
    }

    /**
     * Dry-run: arma el snapshot y devuelve el plan sin escribir.
     *
     * @return array{lotes: list<array<string, mixed>>, ordenes_compra: list<array<string, mixed>>}
     */
    public function simularConfirmacion(Presupuesto $presupuesto): array
    {
        return $this->planificador->planificar(
            $this->construirSnapshotConfirmacion($presupuesto)
        );
    }

    /**
     * Genera OC y lotes según el plan (sin reservar). Usado desde Análisis de Demanda.
     *
     * @return array<int, OrdenCompra>
     */
    public function generarOrdenCompraAutomatica(Presupuesto $presupuesto): array
    {
        $plan = $this->simularConfirmacion($presupuesto);
        $persistido = $this->persistirPlanConfirmacion($presupuesto, $plan);

        return $persistido['ordenes'];
    }

    /**
     * Crea un lote externo de mobiliario (casco u otro) con cantidad explícita.
     */
    public function crearLoteCascoMobiliario(
        Presupuesto $presupuesto,
        int $mobiliarioId,
        int $cantidad,
        string $observaciones
    ): ?LoteProcesoExterno {
        return $this->crearLoteManual(
            $presupuesto,
            'mobiliario',
            $mobiliarioId,
            $cantidad,
            $observaciones,
        );
    }

    /**
     * @param  array{lotes: list<array<string, mixed>>, ordenes_compra: list<array<string, mixed>>}  $plan
     * @return array{lotes: list<LoteProcesoExterno>, ordenes: list<OrdenCompra>}
     */
    public function persistirPlanConfirmacion(Presupuesto $presupuesto, array $plan): array
    {
        return DB::transaction(function () use ($presupuesto, $plan) {
            $lotes = [];

            foreach ($plan['lotes'] ?? [] as $lotePlan) {
                $lote = $this->crearLoteManual(
                    $presupuesto,
                    (string) $lotePlan['entidad_tipo'],
                    (int) $lotePlan['entidad_id'],
                    (float) $lotePlan['cantidad'],
                    (string) ($lotePlan['observaciones'] ?? ''),
                    isset($lotePlan['plantilla_id']) ? (int) $lotePlan['plantilla_id'] : null,
                );

                if ($lote) {
                    $lotes[] = $lote;
                }
            }

            $ordenes = $this->persistirOrdenesDelPlan($presupuesto, $plan['ordenes_compra'] ?? []);

            return [
                'lotes'   => $lotes,
                'ordenes' => $ordenes,
            ];
        });
    }

    /**
     * Snapshot de lectura para el planificador (sin mutar stock ni reservas).
     *
     * @return array<string, mixed>
     */
    public function construirSnapshotConfirmacion(Presupuesto $presupuesto): array
    {
        $presupuesto->loadMissing([
            'items.mobiliario.categoria',
            'items.mobiliario.composicionTecnica',
            'items.insumo',
            'agencia.proyecto.marca',
        ]);

        return [
            'presupuesto'        => [
                'id'      => (int) $presupuesto->id,
                'codigo'  => (string) $presupuesto->codigo,
                'agencia' => $presupuesto->agencia?->nombre ?? '',
                'marca'   => $presupuesto->agencia?->proyecto?->marca?->nombre ?? '',
            ],
            'omitir_oc'          => OrdenCompra::where('presupuesto_id', $presupuesto->id)
                ->where('generado_automaticamente', true)
                ->exists(),
            'mobiliarios'        => $this->snapshotMobiliarios($presupuesto),
            'insumos'            => $this->snapshotInsumos($presupuesto),
            'ordenes_pendientes' => $this->snapshotOrdenesPendientes(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function snapshotMobiliarios(Presupuesto $presupuesto): array
    {
        $grupos = $presupuesto->items
            ->filter(fn (PresupuestoItem $item) => (bool) $item->mobiliario_id)
            ->groupBy('mobiliario_id');

        $lotesExistentes = LoteProcesoExterno::query()
            ->where('entidad_tipo', 'mobiliario')
            ->where('origen_tipo', 'manual')
            ->where('origen_id', $presupuesto->id)
            ->whereNotIn('estado', ['cancelado'])
            ->pluck('entidad_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $resultado = [];

        foreach ($grupos as $mobiliarioId => $items) {
            /** @var PresupuestoItem $primero */
            $primero = $items->first();
            $mobiliario = $primero->mobiliario;
            $plantilla = $mobiliario
                ?->plantillaFlujos()
                ->where('activo', true)
                ->first();

            $esCasco = (bool) $plantilla?->esCascoSilla();
            $cantidadAFabricar = $this->cantidadAFabricarGrupo($items);

            $fila = [
                'id'                  => (int) $mobiliarioId,
                'nombre'              => $mobiliario?->nombre,
                'cantidad_a_fabricar' => $cantidadAFabricar,
                'es_casco'            => $esCasco,
                'ya_tiene_lote'       => in_array((int) $mobiliarioId, $lotesExistentes, true),
                'plantilla'           => $plantilla ? [
                    'id'     => (int) $plantilla->id,
                    'nombre' => $plantilla->nombre,
                ] : null,
            ];

            if ($esCasco && $plantilla) {
                $fila['stock_casco']     = (int) $plantilla->stock_casco;
                $fila['demanda_activa']  = $this->demandaActivaParaPlan($presupuesto, (int) $mobiliarioId);
                $fila['lotes_abiertos']  = $this->stockCasco->cantidadEnLotesAbiertos(
                    (int) $mobiliarioId,
                    (int) $plantilla->id,
                );
            }

            $resultado[] = $fila;
        }

        return $resultado;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, PresupuestoItem>  $items
     */
    private function cantidadAFabricarGrupo($items): int
    {
        $yaAsignado = $items->every(fn (PresupuestoItem $item) => $item->stock_descontado_at !== null);

        if ($yaAsignado) {
            return (int) $items->sum(fn (PresupuestoItem $item) => $item->cantidadParaFabricacion());
        }

        $stockRestante = (int) ($items->first()?->mobiliario?->stock_actual ?? 0);
        $aFabricar = 0;

        foreach ($items as $item) {
            $cantidad = (int) $item->cantidad;
            $desdeStock = min($cantidad, max(0, $stockRestante));
            $stockRestante -= $desdeStock;
            $aFabricar += $cantidad - $desdeStock;
        }

        return $aFabricar;
    }

    private function demandaActivaParaPlan(Presupuesto $presupuesto, int $mobiliarioId): int
    {
        $demanda = $this->stockCasco->demandaActiva($mobiliarioId);

        if (! in_array($presupuesto->estado, StockCascoService::ESTADOS_ACTIVOS, true)) {
            $demanda += (int) $presupuesto->items
                ->where('mobiliario_id', $mobiliarioId)
                ->whereNull('finalizado_at')
                ->sum('cantidad');
        }

        return $demanda;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function snapshotInsumos(Presupuesto $presupuesto): array
    {
        $demanda = $this->calcularDemandaPresupuesto($presupuesto);

        if ($demanda === []) {
            return [];
        }

        $insumoIds = array_map('intval', array_keys($demanda));

        $insumos = Insumo::whereIn('id', $insumoIds)->get()->keyBy('id');

        $plantillas = PlantillaFlujoExterno::query()
            ->where('entidad_tipo', 'insumo')
            ->whereIn('entidad_id', $insumoIds)
            ->where('activo', true)
            ->get()
            ->keyBy('entidad_id');

        $reservas = ReservaStock::query()
            ->where('estado', 'activa')
            ->whereIn('insumo_id', $insumoIds)
            ->get()
            ->groupBy('insumo_id');

        $lotesExistentes = LoteProcesoExterno::query()
            ->where('entidad_tipo', 'insumo')
            ->where('origen_tipo', 'manual')
            ->where('origen_id', $presupuesto->id)
            ->whereNotIn('estado', ['cancelado'])
            ->pluck('entidad_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $resultado = [];

        foreach ($demanda as $insumoId => $cantidadDemanda) {
            $insumoId = (int) $insumoId;
            /** @var Insumo|null $insumo */
            $insumo = $insumos->get($insumoId);

            if (! $insumo) {
                continue;
            }

            $reservadoAjeno = (float) ($reservas->get($insumoId) ?? collect())
                ->where('presupuesto_id', '!=', $presupuesto->id)
                ->sum('cantidad_reservada');

            $plantilla = $plantillas->get($insumoId);

            $resultado[] = [
                'id'                => $insumoId,
                'nombre'            => $insumo->nombre,
                'codigo'            => $insumo->codigo,
                'demanda'           => (float) $cantidadDemanda,
                'stock_disponible'  => max(0, (float) ($insumo->stock_actual ?? 0) - $reservadoAjeno),
                'proveedor_id'      => $insumo->proveedor_id ? (int) $insumo->proveedor_id : null,
                'precio_costo'      => $insumo->precio_costo,
                'ya_tiene_lote'     => in_array($insumoId, $lotesExistentes, true),
                'plantilla'         => $plantilla ? [
                    'id'     => (int) $plantilla->id,
                    'nombre' => $plantilla->nombre,
                ] : null,
            ];
        }

        return $resultado;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function snapshotOrdenesPendientes(): array
    {
        return OrdenCompra::query()
            ->whereIn('estado', ['sugerida', 'pendiente'])
            ->with('items')
            ->get()
            ->map(fn (OrdenCompra $oc) => [
                'id'           => (int) $oc->id,
                'proveedor_id' => $oc->proveedor_id ? (int) $oc->proveedor_id : null,
                'estado'       => $oc->estado,
                'prioridad'    => $oc->prioridad,
                'insumo_ids'   => $oc->items
                    ->pluck('insumo_id')
                    ->map(fn ($id) => (int) $id)
                    ->values()
                    ->all(),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $ordenesPlan
     * @return list<OrdenCompra>
     */
    private function persistirOrdenesDelPlan(Presupuesto $presupuesto, array $ordenesPlan): array
    {
        $ordenes = [];

        foreach ($ordenesPlan as $ocPlan) {
            $oc = null;

            if (($ocPlan['accion'] ?? '') === 'agregar' && ! empty($ocPlan['orden_compra_id'])) {
                $oc = OrdenCompra::find($ocPlan['orden_compra_id']);

                if ($oc && ($ocPlan['prioridad'] ?? '') === 'critica' && $oc->prioridad !== 'critica') {
                    $oc->update(['prioridad' => 'critica']);
                }
            }

            if (! $oc) {
                $oc = OrdenCompra::create([
                    'estado'                   => 'sugerida',
                    'prioridad'                => $ocPlan['prioridad'] ?? 'alta',
                    'generado_automaticamente' => true,
                    'presupuesto_id'           => $presupuesto->id,
                    'proveedor_id'             => $ocPlan['proveedor_id'] ?? null,
                    'observaciones'            => "Generada automáticamente para presupuesto {$presupuesto->codigo}",
                ]);
            }

            foreach ($ocPlan['items'] ?? [] as $itemPlan) {
                $itemExistente = OrdenCompraItem::where('orden_compra_id', $oc->id)
                    ->where('insumo_id', $itemPlan['insumo_id'])
                    ->first();

                if ($itemExistente) {
                    $itemExistente->update([
                        'cantidad_solicitada' => $itemExistente->cantidad_solicitada + $itemPlan['cantidad'],
                        'precio_unitario'     => $itemPlan['precio_unitario'] ?? $itemExistente->precio_unitario,
                    ]);
                } else {
                    OrdenCompraItem::create([
                        'orden_compra_id'     => $oc->id,
                        'insumo_id'           => $itemPlan['insumo_id'],
                        'cantidad_solicitada' => $itemPlan['cantidad'],
                        'precio_unitario'     => $itemPlan['precio_unitario'] ?? null,
                    ]);
                }
            }

            $ordenes[] = $oc;
        }

        return $ordenes;
    }

    public function crearLoteManual(
        Presupuesto $presupuesto,
        string $entidadTipo,
        int $entidadId,
        float $cantidad,
        string $observaciones,
        ?int $plantillaId = null
    ): ?LoteProcesoExterno {
        if ($cantidad <= 0) {
            return null;
        }

        $plantillaQuery = PlantillaFlujoExterno::query()
            ->where('entidad_tipo', $entidadTipo)
            ->where('entidad_id', $entidadId)
            ->where('activo', true)
            ->with('etapas');

        $plantilla = $plantillaId
            ? (clone $plantillaQuery)->whereKey($plantillaId)->first() ?? $plantillaQuery->first()
            : $plantillaQuery->first();

        if (! $plantilla) {
            return null;
        }

        $lote = LoteProcesoExterno::create([
            'plantilla_id'  => $plantilla->id,
            'entidad_tipo'  => $entidadTipo,
            'entidad_id'    => $entidadId,
            'cantidad'      => $cantidad,
            'origen_tipo'   => 'manual',
            'origen_id'     => $presupuesto->id,
            'estado'        => 'pendiente',
            'fecha_inicio'  => now()->toDateString(),
            'observaciones' => $observaciones,
        ]);

        $lote->crearEtapasDesde($plantilla);

        return $lote;
    }

    // ─── Internals ────────────────────────────────────────────────────────────

    private function calcularDemandaPresupuesto(Presupuesto $presupuesto): array
    {
        $presupuesto->loadMissing([
            'items.mobiliario.composicionTecnica',
            'items.mobiliario.categoria',
            'items.insumo',
        ]);

        $demanda = [];

        foreach ($presupuesto->items as $item) {
            foreach ($item->demandaInsumos() as $insumoId => $cantidad) {
                $demanda[$insumoId] = ($demanda[$insumoId] ?? 0) + $cantidad;
            }
        }

        foreach ($this->stockCasco->demandaInsumosCascoPresupuesto($presupuesto) as $insumoId => $cantidad) {
            $demanda[$insumoId] = ($demanda[$insumoId] ?? 0) + $cantidad;
        }

        return $demanda;
    }

    /** Demanda de insumos solo para ítems aún no finalizados. */
    private function calcularDemandaPendiente(Presupuesto $presupuesto): array
    {
        $presupuesto->loadMissing([
            'items.mobiliario.composicionTecnica',
            'items.mobiliario.categoria',
            'items.insumo',
        ]);

        $demanda = [];

        foreach ($presupuesto->items->whereNull('finalizado_at') as $item) {
            foreach ($item->demandaInsumos() as $insumoId => $cantidad) {
                $demanda[$insumoId] = ($demanda[$insumoId] ?? 0) + $cantidad;
            }
        }

        foreach ($this->stockCasco->demandaInsumosCascoPresupuesto($presupuesto) as $insumoId => $cantidad) {
            $demanda[$insumoId] = ($demanda[$insumoId] ?? 0) + $cantidad;
        }

        return $demanda;
    }
}
