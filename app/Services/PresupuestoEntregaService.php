<?php

namespace App\Services;

use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use Illuminate\Support\Facades\DB;

class PresupuestoEntregaService
{
    public function puedeRegistrarEntrega(Presupuesto $presupuesto): bool
    {
        return in_array($presupuesto->estado, ['confirmado', 'pagado', 'entregado_parcial', 'entregado'], true);
    }

    public function marcarEntregaCompleta(Presupuesto $presupuesto): void
    {
        DB::transaction(function () use ($presupuesto): void {
            $presupuesto->loadMissing('items');

            foreach ($presupuesto->items as $item) {
                $this->marcarItemEntregado($item);
            }

            $this->sincronizarEstadoPresupuesto($presupuesto);
        });
    }

    /**
     * @param  array<int>  $itemIds
     */
    public function marcarEntregaParcial(Presupuesto $presupuesto, array $itemIds): void
    {
        DB::transaction(function () use ($presupuesto, $itemIds): void {
            $presupuesto->loadMissing('items');
            $itemIds = array_map('intval', $itemIds);

            foreach ($presupuesto->items as $item) {
                if (in_array($item->id, $itemIds, true)) {
                    $this->marcarItemEntregado($item);
                } else {
                    $this->desmarcarItemEntregado($item);
                }
            }

            $this->sincronizarEstadoPresupuesto($presupuesto);
        });
    }

    public function toggleItemEntrega(PresupuestoItem $item, bool $entregado, ?string $observaciones = null): void
    {
        DB::transaction(function () use ($item, $entregado, $observaciones): void {
            if ($entregado) {
                $this->marcarItemEntregado($item, $observaciones);
            } else {
                $this->desmarcarItemEntregado($item);
            }

            $this->sincronizarEstadoPresupuesto($item->presupuesto);
        });
    }

    public function marcarItemEntregado(PresupuestoItem $item, ?string $observaciones = null): void
    {
        $userId = auth()->check() ? auth()->id() : null;

        $item->update([
            'entregado_at'           => $item->entregado_at ?? now(),
            'entregado_por'          => $item->entregado_por ?? $userId,
            'entrega_observaciones'  => $observaciones ?? $item->entrega_observaciones,
        ]);
    }

    public function desmarcarItemEntregado(PresupuestoItem $item): void
    {
        $item->update([
            'entregado_at'          => null,
            'entregado_por'         => null,
            'entrega_observaciones' => null,
        ]);
    }

    public function sincronizarEstadoPresupuesto(Presupuesto $presupuesto): void
    {
        $presupuesto->loadMissing('items');

        $total = $presupuesto->items->count();
        $entregados = $presupuesto->items->filter(fn (PresupuestoItem $item) => $item->estaEntregado())->count();

        if ($total === 0) {
            return;
        }

        if ($entregados === 0) {
            if (in_array($presupuesto->estado, ['entregado_parcial', 'entregado'], true)) {
                $presupuesto->cambiarEstado($this->estadoBasePreEntrega($presupuesto));
            }

            return;
        }

        if ($entregados === $total) {
            if ($presupuesto->estado !== 'entregado') {
                $presupuesto->cambiarEstado('entregado');
            }

            return;
        }

        if ($presupuesto->estado !== 'entregado_parcial') {
            $presupuesto->cambiarEstado('entregado_parcial');
        }
    }

    private function estadoBasePreEntrega(Presupuesto $presupuesto): string
    {
        $fuePagado = $presupuesto->historial()
            ->where('estado_nuevo', 'pagado')
            ->exists();

        return $fuePagado ? 'pagado' : 'confirmado';
    }
}
