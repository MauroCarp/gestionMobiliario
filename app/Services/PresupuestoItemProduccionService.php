<?php

namespace App\Services;

use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Models\PresupuestoItemEtapa;
use Illuminate\Support\Facades\DB;

class PresupuestoItemProduccionService
{
    const ETAPA_INICIO = 'Inicio';

    const ETAPAS_PREDETERMINADAS = [
        1 => 'Inicio',
        2 => 'Seccionado',
        3 => 'Pegado de filos',
        4 => 'Pantografiado',
        5 => 'Armado',
        6 => 'Terminacion',
        7 => 'Embalado',
    ];

    public function crearEtapasParaPresupuesto(Presupuesto $presupuesto): void
    {
        $presupuesto->loadMissing('items');

        foreach ($presupuesto->items as $item) {
            $this->crearEtapasParaItem($item);
        }
    }

    public function crearEtapasParaItem(PresupuestoItem $item): void
    {
        if (! $item->mobiliario_id || $item->cantidadParaFabricacion() <= 0) {
            return;
        }

        foreach (self::ETAPAS_PREDETERMINADAS as $orden => $nombre) {
            PresupuestoItemEtapa::firstOrCreate(
                [
                    'presupuesto_item_id' => $item->id,
                    'orden'               => $orden,
                ],
                [
                    'nombre'  => $nombre,
                    'estado'  => 'pendiente',
                ],
            );
        }
    }

    public function actualizarEtapas(PresupuestoItem $item, array $etapas): void
    {
        if (! $item->mobiliario_id || $item->cantidadParaFabricacion() <= 0) {
            return;
        }

        DB::transaction(function () use ($item, $etapas): void {
            $this->crearEtapasParaItem($item);

            foreach ($etapas as $etapaData) {
                if (empty($etapaData['id'])) {
                    continue;
                }

                $etapa = $item->etapasProduccion()->find($etapaData['id']);

                if (! $etapa) {
                    continue;
                }

                $estado = $etapaData['estado'] ?? $etapa->estado;
                $fechaInicio = $etapaData['fecha_inicio'] ?? null;
                $fechaFin = $etapaData['fecha_fin'] ?? null;
                $userId = auth()->check() ? auth()->id() : null;

                if ($this->esEtapaInicio($etapa)) {
                    $estado = in_array($estado, ['pendiente', 'completado'], true) ? $estado : 'pendiente';

                    $data = [
                        'estado'         => $estado,
                        'fecha_inicio'   => $estado === 'completado' ? ($fechaInicio ?: now()->toDateString()) : null,
                        'fecha_fin'      => null,
                        'iniciado_por'   => null,
                        'completado_por' => null,
                        'observaciones'  => null,
                    ];

                    $etapa->update($data);

                    continue;
                }

                $data = [
                    'estado'        => array_key_exists($estado, PresupuestoItemEtapa::ESTADOS) ? $estado : $etapa->estado,
                    'fecha_inicio'  => $fechaInicio ?: null,
                    'fecha_fin'     => $fechaFin ?: null,
                    'observaciones' => $etapaData['observaciones'] ?? null,
                ];

                if ($data['estado'] === 'en_proceso') {
                    $data['fecha_inicio'] = $data['fecha_inicio'] ?: now()->toDateString();
                    $data['iniciado_por'] = $etapa->iniciado_por ?: $userId;
                }

                if ($data['estado'] === 'completado') {
                    $data['fecha_inicio'] = $data['fecha_inicio'] ?: now()->toDateString();
                    $data['fecha_fin'] = $data['fecha_fin'] ?: now()->toDateString();
                    $data['iniciado_por'] = $etapa->iniciado_por ?: $userId;
                    $data['completado_por'] = $etapa->completado_por ?: $userId;
                }

                $etapa->update($data);
            }
        });
    }

    public function esEtapaInicio(PresupuestoItemEtapa $etapa): bool
    {
        return $etapa->orden === 1 || $etapa->nombre === self::ETAPA_INICIO;
    }
}
