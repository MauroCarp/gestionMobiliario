<?php

namespace App\Services;

use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Models\PresupuestoItemEntrega;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PresupuestoEntregaService
{
    public function puedeRegistrarEntrega(Presupuesto $presupuesto): bool
    {
        return in_array($presupuesto->estado, ['confirmado', 'pagado', 'entregado_parcial', 'entregado'], true);
    }

    public function marcarEntregaCompleta(Presupuesto $presupuesto): void
    {
        DB::transaction(function () use ($presupuesto): void {
            $presupuesto = $this->bloquearPresupuesto($presupuesto);
            $presupuesto->loadMissing('items');

            $bloqueados = $presupuesto->items
                ->filter(fn (PresupuestoItem $item): bool => $item->cantidadPendiente() > 0 && ! $item->puedeRecibirEntrega());

            if ($bloqueados->isNotEmpty()) {
                throw new InvalidArgumentException(
                    'Hay ítems pendientes que requieren finalizar la producción antes de entregar el presupuesto completo.',
                );
            }

            foreach ($presupuesto->items as $item) {
                $pendiente = $item->cantidadPendiente();

                if ($pendiente <= 0) {
                    continue;
                }

                $this->crearMovimiento($item, $pendiente);
            }

            $this->sincronizarEstadoPresupuesto($presupuesto->fresh('items'));
        });
    }

    /**
     * @param  array<int, int>  $cantidadesPorItem
     */
    public function marcarEntregaParcial(Presupuesto $presupuesto, array $cantidadesPorItem): void
    {
        $cantidadesPorItem = $this->normalizarCantidades($cantidadesPorItem);

        if ($cantidadesPorItem === []) {
            throw new InvalidArgumentException('Debe indicar al menos una cantidad a entregar.');
        }

        DB::transaction(function () use ($presupuesto, $cantidadesPorItem): void {
            $presupuesto = $this->bloquearPresupuesto($presupuesto);
            $presupuesto->loadMissing('items');

            foreach ($cantidadesPorItem as $itemId => $cantidad) {
                $item = $presupuesto->items->firstWhere('id', $itemId);

                if (! $item) {
                    throw new InvalidArgumentException('El ítem no pertenece al presupuesto.');
                }

                $item = $this->bloquearItem($item);
                $this->crearMovimiento($item, $cantidad);
            }

            $this->sincronizarEstadoPresupuesto($presupuesto->fresh('items'));
        });
    }

    public function registrarEntregaItem(PresupuestoItem $item, int $cantidad, ?string $observaciones = null): void
    {
        DB::transaction(function () use ($item, $cantidad, $observaciones): void {
            $item = $this->bloquearItem($item);

            if (! $item->presupuesto || ! $this->puedeRegistrarEntrega($item->presupuesto)) {
                throw new InvalidArgumentException('El presupuesto no admite el registro de entregas.');
            }

            $this->crearMovimiento($item, $cantidad, $observaciones);
            $this->sincronizarEstadoPresupuesto($this->bloquearPresupuesto($item->presupuesto));
        });
    }

    public function anularEntrega(PresupuestoItemEntrega $entrega, string $motivo): void
    {
        $motivo = trim($motivo);

        if ($motivo === '') {
            throw new InvalidArgumentException('Debe indicar el motivo de la anulación.');
        }

        DB::transaction(function () use ($entrega, $motivo): void {
            $entrega = PresupuestoItemEntrega::query()
                ->whereKey($entrega->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($entrega->estaAnulada()) {
                throw new InvalidArgumentException('El movimiento de entrega ya fue anulado.');
            }

            $item = $this->bloquearItem($entrega->item);

            if (! $item->presupuesto || ! $this->puedeRegistrarEntrega($item->presupuesto)) {
                throw new InvalidArgumentException('El presupuesto no admite la anulación de entregas.');
            }

            $entrega->update([
                'anulado_at'        => now(),
                'anulado_por'       => auth()->check() ? auth()->id() : null,
                'motivo_anulacion'  => $motivo,
            ]);

            $this->recalcularAcumulado($item);
            $this->sincronizarEstadoPresupuesto($this->bloquearPresupuesto($item->presupuesto));
        });
    }

    public function sincronizarEstadoPresupuesto(Presupuesto $presupuesto): void
    {
        $presupuesto->loadMissing('items');

        $total = (int) $presupuesto->items->sum('cantidad');
        $entregadas = (int) $presupuesto->items->sum('cantidad_entregada');

        if ($total === 0) {
            return;
        }

        if ($entregadas === 0) {
            if (in_array($presupuesto->estado, ['entregado_parcial', 'entregado'], true)) {
                $presupuesto->cambiarEstado($this->estadoBasePreEntrega($presupuesto));
            }

            return;
        }

        if ($entregadas >= $total && $presupuesto->items->every(fn (PresupuestoItem $item): bool => $item->estaEntregado())) {
            if ($presupuesto->estado !== 'entregado') {
                $presupuesto->cambiarEstado('entregado');
            }

            return;
        }

        if ($presupuesto->estado !== 'entregado_parcial') {
            $presupuesto->cambiarEstado('entregado_parcial');
        }
    }

    private function crearMovimiento(PresupuestoItem $item, int $cantidad, ?string $observaciones = null): void
    {
        $this->assertCantidadValida($item, $cantidad);

        $ahora = now();
        $userId = auth()->check() ? auth()->id() : null;

        PresupuestoItemEntrega::create([
            'presupuesto_item_id' => $item->id,
            'cantidad'            => $cantidad,
            'entregado_at'        => $ahora,
            'entregado_por'       => $userId,
            'observaciones'       => $observaciones,
        ]);

        $item->update([
            'cantidad_entregada'    => (int) $item->cantidad_entregada + $cantidad,
            'entregado_at'          => $ahora,
            'entregado_por'         => $userId ?? $item->entregado_por,
            'entrega_observaciones' => $observaciones ?? $item->entrega_observaciones,
        ]);

        $item->refresh();
    }

    private function recalcularAcumulado(PresupuestoItem $item): void
    {
        $activas = $item->entregas()->activas()->orderByDesc('entregado_at')->orderByDesc('id')->get();
        $ultima = $activas->first();

        $item->update([
            'cantidad_entregada'    => (int) $activas->sum('cantidad'),
            'entregado_at'          => $ultima?->entregado_at,
            'entregado_por'         => $ultima?->entregado_por,
            'entrega_observaciones' => $ultima?->observaciones,
        ]);

        $item->refresh();
    }

    private function assertCantidadValida(PresupuestoItem $item, int $cantidad): void
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException('La cantidad entregada debe ser mayor a cero.');
        }

        if ($cantidad > $item->cantidadPendiente()) {
            throw new InvalidArgumentException('La cantidad entregada supera la cantidad pendiente.');
        }

        if ($item->requiereFabricacion() && ! $item->estaFinalizado()) {
            throw new InvalidArgumentException('El ítem debe estar finalizado para registrar una entrega.');
        }
    }

    /**
     * @param  array<int|string, mixed>  $cantidadesPorItem
     * @return array<int, int>
     */
    private function normalizarCantidades(array $cantidadesPorItem): array
    {
        $normalizadas = [];

        foreach ($cantidadesPorItem as $itemId => $cantidad) {
            $id = (int) $itemId;
            $valor = (int) $cantidad;

            if ($id > 0 && $valor > 0) {
                $normalizadas[$id] = $valor;
            }
        }

        return $normalizadas;
    }

    private function bloquearItem(PresupuestoItem $item): PresupuestoItem
    {
        return PresupuestoItem::query()
            ->whereKey($item->id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    private function bloquearPresupuesto(Presupuesto $presupuesto): Presupuesto
    {
        return Presupuesto::query()
            ->whereKey($presupuesto->id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    private function estadoBasePreEntrega(Presupuesto $presupuesto): string
    {
        $fuePagado = $presupuesto->historial()
            ->where('estado_nuevo', 'pagado')
            ->exists();

        return $fuePagado ? 'pagado' : 'confirmado';
    }
}
