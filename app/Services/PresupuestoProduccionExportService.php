<?php

namespace App\Services;

use App\Models\Insumo;
use App\Models\Presupuesto;
use Illuminate\Support\Collection;

/**
 * Prepara los datos de exportación de producción (PDF / Excel) desde un presupuesto.
 */
class PresupuestoProduccionExportService
{
    public function prepare(Presupuesto $presupuesto): array
    {
        $presupuesto->load([
            'agencia.proyecto.marca',
            'agencia.provincia',
            'agencia.ciudad',
            'responsable',
            'aprobadoPor',
            'items' => fn ($q) => $q->orderBy('sector_id')->orderBy('orden')
                ->with([
                    'mobiliario.categoria',
                    'mobiliario.composicionTecnica.insumo.unidadMedida',
                    'mobiliario.plantillaFlujos' => fn ($q) => $q
                        ->where('activo', true)
                        ->with(['etapas.tipoProceso', 'etapas.tercero']),
                    'sector',
                    'insumo.unidadMedida',
                    'insumo.categoriasInsumo',
                ]),
        ]);

        $agencia  = $presupuesto->agencia;
        $proyecto = $agencia?->proyecto;
        $marca    = $proyecto?->marca;

        $items = $presupuesto->items->map(function ($item) {
            return [
                'item'       => $item,
                'mobiliario' => $item->mobiliario,
                'insumo'     => $item->insumo,
                'sector'     => $item->sector,
            ];
        });

        $itemsPorSector = $items->groupBy(function ($itemData) {
            return $itemData['sector']?->nombre ?? '__sin_sector__';
        });

        $itemsConMobiliario = $items->filter(fn ($d) => $d['mobiliario'] !== null);

        $resumenInsumos = $this->calcularResumenInsumos($items);
        $resumenInsumos = $this->enriquecerInsumosConStock($resumenInsumos);

        return compact(
            'presupuesto',
            'agencia',
            'proyecto',
            'marca',
            'items',
            'itemsPorSector',
            'itemsConMobiliario',
            'resumenInsumos',
        );
    }

    /**
     * @param  Collection<int, array>  $items
     * @return array<int, array{insumo: \App\Models\Insumo|null, unidad: string, total: float}>
     */
    private function calcularResumenInsumos(Collection $items): array
    {
        $resumenInsumos = [];

        foreach ($items->filter(fn ($d) => $d['mobiliario'] !== null) as $itemData) {
            $item        = $itemData['item'];
            $mob         = $itemData['mobiliario'];
            $cantItem    = (int) $item->cantidad;
            $composicion = $mob->composicionTecnica;

            foreach ($composicion as $comp) {
                $insumoComp = $comp->insumo;
                $cantUnit   = (float) $comp->cantidad;
                $cantTotal  = $cantUnit * $cantItem;
                $unidad     = $insumoComp?->unidadMedida?->nombre ?? '—';
                $insId      = $insumoComp?->id;

                if (! $insId) {
                    continue;
                }

                if (isset($resumenInsumos[$insId])) {
                    $resumenInsumos[$insId]['total'] += $cantTotal;
                } else {
                    $resumenInsumos[$insId] = [
                        'insumo' => $insumoComp,
                        'unidad' => $unidad,
                        'total'  => $cantTotal,
                    ];
                }
            }
        }

        foreach ($items->filter(fn ($d) => $d['insumo'] !== null) as $itemData) {
            $insumo = $itemData['insumo'];
            $insId  = $insumo?->id;

            if (! $insId) {
                continue;
            }

            $cant   = (float) $itemData['item']->cantidad;
            $unidad = $insumo->unidadMedida?->nombre ?? '—';

            if (isset($resumenInsumos[$insId])) {
                $resumenInsumos[$insId]['total'] += $cant;
            } else {
                $resumenInsumos[$insId] = [
                    'insumo' => $insumo,
                    'unidad' => $unidad,
                    'total'  => $cant,
                ];
            }
        }

        return $resumenInsumos;
    }

    /**
     * @param  array<int, array{insumo: \App\Models\Insumo|null, unidad: string, total: float}>  $resumenInsumos
     * @return array<int, array{insumo: \App\Models\Insumo|null, unidad: string, total: float}>
     */
    private function enriquecerInsumosConStock(array $resumenInsumos): array
    {
        if (empty($resumenInsumos)) {
            return $resumenInsumos;
        }

        $insumos = Insumo::query()
            ->whereIn('id', array_keys($resumenInsumos))
            ->with('reservasActivas')
            ->get()
            ->keyBy('id');

        foreach ($resumenInsumos as $insumoId => $row) {
            if ($insumos->has($insumoId)) {
                $resumenInsumos[$insumoId]['insumo'] = $insumos[$insumoId];
            }
        }

        return $resumenInsumos;
    }
}
