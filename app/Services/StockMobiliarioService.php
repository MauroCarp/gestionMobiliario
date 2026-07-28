<?php

namespace App\Services;

use App\Models\Mobiliario;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use Illuminate\Support\Facades\DB;

class StockMobiliarioService
{
    /**
     * Asigna stock disponible a cada ítem de mobiliario y descuenta el inventario.
     * Idempotente: no reprocesa ítems ya descontados.
     */
    public function asignarStockPresupuesto(Presupuesto $presupuesto): void
    {
        DB::transaction(function () use ($presupuesto): void {
            $presupuesto->loadMissing('items');

            foreach ($presupuesto->items as $item) {
                $this->asignarStockItem($item);
            }
        });
    }

    public function asignarStockItem(PresupuestoItem $item): void
    {
        if (! $item->mobiliario_id || $item->stock_descontado_at !== null) {
            return;
        }

        DB::transaction(function () use ($item): void {
            $mobiliario = Mobiliario::query()
                ->whereKey($item->mobiliario_id)
                ->lockForUpdate()
                ->first();

            if (! $mobiliario) {
                return;
            }

            $desdeStock = min((int) $item->cantidad, max(0, (int) $mobiliario->stock_actual));
            $aFabricar  = (int) $item->cantidad - $desdeStock;

            if ($desdeStock > 0) {
                $mobiliario->decrement('stock_actual', $desdeStock);
            }

            $item->update([
                'cantidad_desde_stock' => $desdeStock,
                'cantidad_a_fabricar'  => $aFabricar,
                'stock_descontado_at'  => now(),
            ]);
        });
    }

    /**
     * Reintegra el stock descontado al cancelar o rechazar un presupuesto.
     * Idempotente: no reprocesa ítems ya reintegrados.
     */
    public function reintegrarStockPresupuesto(Presupuesto $presupuesto): void
    {
        DB::transaction(function () use ($presupuesto): void {
            $presupuesto->loadMissing('items');

            foreach ($presupuesto->items as $item) {
                $this->reintegrarStockItem($item);
            }
        });
    }

    public function reintegrarStockItem(PresupuestoItem $item): void
    {
        if (
            ! $item->mobiliario_id
            || $item->stock_descontado_at === null
            || $item->stock_reintegrado_at !== null
        ) {
            return;
        }

        DB::transaction(function () use ($item): void {
            if ($item->cantidad_desde_stock > 0) {
                Mobiliario::query()
                    ->whereKey($item->mobiliario_id)
                    ->increment('stock_actual', $item->cantidad_desde_stock);
            }

            $item->update(['stock_reintegrado_at' => now()]);
        });
    }
}
