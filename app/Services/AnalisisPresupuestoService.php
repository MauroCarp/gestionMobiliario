<?php

namespace App\Services;

use App\Models\Insumo;
use App\Models\Presupuesto;
use Illuminate\Support\Collection;

/**
 * Calcula la demanda de insumos para uno o varios presupuestos,
 * comparando contra el stock disponible y generando alertas.
 */
class AnalisisPresupuestoService
{
    /**
     * Analiza un presupuesto individual.
     * Retorna array con insumos requeridos, disponibles, faltantes y críticos.
     */
    public function analizar(Presupuesto $presupuesto): array
    {
        $presupuesto->loadMissing([
            'items.mobiliario.composicionTecnica.insumo.unidadMedida',
        ]);

        $demanda = $this->calcularDemanda(collect([$presupuesto]));

        return $this->construirResultado($demanda);
    }

    /**
     * Análisis global: todos los presupuestos confirmados/pagados con stock activo.
     */
    public function analizarGlobal(): array
    {
        $presupuestos = Presupuesto::whereIn('estado', ['confirmado', 'pagado'])
            ->with(['items.mobiliario.composicionTecnica.insumo.unidadMedida'])
            ->get();

        $demanda = $this->calcularDemanda($presupuestos);

        return $this->construirResultado($demanda);
    }

    /**
     * Demanda de todos los presupuestos aprobados o superior (futura demanda).
     */
    public function analizarDemandaFutura(): array
    {
        $presupuestos = Presupuesto::whereIn('estado', ['aprobado', 'confirmado', 'pagado'])
            ->with(['items.mobiliario.composicionTecnica.insumo.unidadMedida'])
            ->get();

        $demanda = $this->calcularDemanda($presupuestos);

        return $this->construirResultado($demanda);
    }

    /**
     * Retorna estadísticas resumidas para widgets.
     */
    public function estadisticas(): array
    {
        $insumos = Insumo::where('activo', true)
            ->with('reservasActivas')
            ->get();

        $totalInsumos       = $insumos->count();
        $criticos           = $insumos->filter(fn ($i) => $i->es_critico)->count();
        $sinStock           = $insumos->filter(fn ($i) => ($i->stock_actual ?? 0) == 0)->count();
        $conReserva         = $insumos->filter(fn ($i) => $i->stock_reservado > 0)->count();

        $presupuestosPendientes = Presupuesto::whereIn('estado', ['aprobado', 'confirmado'])->count();
        $presupuestosPagados    = Presupuesto::where('estado', 'pagado')->count();

        $demandaFutura    = $this->analizarDemandaFutura();
        $totalFaltantes   = collect($demandaFutura)->where('faltante', '>', 0)->count();
        $valorFaltantes   = collect($demandaFutura)->sum(fn ($r) =>
            $r['faltante'] * ($r['insumo']->precio_costo ?? 0)
        );

        return compact(
            'totalInsumos', 'criticos', 'sinStock', 'conReserva',
            'presupuestosPendientes', 'presupuestosPagados',
            'totalFaltantes', 'valorFaltantes'
        );
    }

    /**
     * Genera sugerencias de órdenes de compra para insumos con faltante o bajo mínimo.
     * Retorna colección de arrays [insumo, cantidad_sugerida, prioridad].
     */
    public function sugerenciasCompra(): Collection
    {
        $resultado = collect($this->analizarDemandaFutura());

        return $resultado
            ->filter(fn ($r) => $r['faltante'] > 0 || $r['insumo']->es_critico)
            ->map(function ($r) {
                $insumo = $r['insumo'];

                // Cuánto hace falta para cubrir demanda + reponer hasta stock mínimo
                $cantidadSugerida = max(
                    $r['faltante'],
                    max(0, ($insumo->stock_minimo ?? 0) - ($insumo->stock_actual ?? 0))
                );

                $prioridad = match (true) {
                    $r['faltante'] > 0 && $insumo->stock_actual == 0 => 'critica',
                    $r['faltante'] > 0                                => 'alta',
                    $insumo->es_critico                               => 'media',
                    default                                           => 'baja',
                };

                return [
                    'insumo'            => $insumo,
                    'demanda_total'     => $r['requerido'],
                    'stock_disponible'  => $r['disponible'],
                    'faltante'          => $r['faltante'],
                    'cantidad_sugerida' => $cantidadSugerida,
                    'prioridad'         => $prioridad,
                    'valor_estimado'    => $cantidadSugerida * ($insumo->precio_costo ?? 0),
                ];
            })
            ->sortByDesc(fn ($r) => match ($r['prioridad']) {
                'critica' => 4,
                'alta'    => 3,
                'media'   => 2,
                default   => 1,
            })
            ->values();
    }

    // ─── Internals ────────────────────────────────────────────────────────────

    /**
     * Suma la demanda de insumos de una colección de presupuestos.
     * Retorna: [insumo_id => cantidad_total]
     */
    private function calcularDemanda(Collection $presupuestos): array
    {
        $demanda = [];

        foreach ($presupuestos as $presupuesto) {
            foreach ($presupuesto->items as $item) {
                $cantMobiliario = (int) $item->cantidad;

                foreach ($item->mobiliario->composicionTecnica as $comp) {
                    $id = $comp->insumo_id;
                    $demanda[$id] = ($demanda[$id] ?? 0) + ($comp->cantidad * $cantMobiliario);
                }
            }
        }

        return $demanda;
    }

    /**
     * Convierte el array de demanda en resultados con stock y alertas.
     */
    private function construirResultado(array $demanda): array
    {
        if (empty($demanda)) {
            return [];
        }

        $insumos = Insumo::whereIn('id', array_keys($demanda))
            ->with('unidadMedida')
            ->get()
            ->keyBy('id');

        $resultado = [];

        foreach ($demanda as $insumoId => $requerido) {
            $insumo    = $insumos[$insumoId] ?? null;
            if (! $insumo) continue;

            $disponible = $insumo->stock_disponible;
            $faltante   = max(0, $requerido - $disponible);

            $resultado[] = [
                'insumo'             => $insumo,
                'requerido'          => round($requerido, 4),
                'disponible'         => round($disponible, 4),
                'reservado'          => round($insumo->stock_reservado, 4),
                'stock_actual'       => round($insumo->stock_actual ?? 0, 4),
                'faltante'           => round($faltante, 4),
                'critico'            => $insumo->es_critico || $faltante > 0,
                'unidad'             => $insumo->unidadMedida?->nombre ?? '—',
            ];
        }

        usort($resultado, fn ($a, $b) => $b['faltante'] <=> $a['faltante']);

        return $resultado;
    }
}
