<?php

namespace App\Services;

use App\Models\LoteProcesoExterno;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraItem;
use App\Models\OrdenCompraItemRecepcion;
use App\Models\PlantillaFlujoExterno;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrdenCompraRecepcionService
{
    /**
     * Registra la recepción total de un ítem de la orden de compra.
     */
    public function registrarRecepcionTotal(OrdenCompraItem $item, ?Carbon $fecha = null): void
    {
        $pendiente = max(0, $item->cantidad_solicitada - $item->cantidad_recibida);

        if ($pendiente <= 0) {
            throw new InvalidArgumentException('El ítem ya fue recibido en su totalidad.');
        }

        $fecha = $fecha ?? now();

        DB::transaction(function () use ($item, $pendiente, $fecha): void {
            $this->registrarRecepcion($item, $pendiente, $fecha, 'Recepción total');
            $this->procesarStockYLotes($item, $pendiente, esRecepcionTotal: true);
            $this->actualizarEstadoOrden($item->ordenCompra);
        });
    }

    /**
     * Registra una recepción parcial de un ítem de la orden de compra.
     */
    public function registrarRecepcionParcial(
        OrdenCompraItem $item,
        float $cantidad,
        Carbon $fecha,
        ?string $notas = null
    ): void {
        $pendiente = max(0, $item->cantidad_solicitada - $item->cantidad_recibida);

        if ($pendiente <= 0) {
            throw new InvalidArgumentException('El ítem ya fue recibido en su totalidad.');
        }

        if ($cantidad <= 0) {
            throw new InvalidArgumentException('La cantidad recibida debe ser mayor a cero.');
        }

        if ($cantidad > $pendiente) {
            throw new InvalidArgumentException('La cantidad recibida supera la cantidad pendiente.');
        }

        DB::transaction(function () use ($item, $cantidad, $fecha, $notas): void {
            $this->registrarRecepcion($item, $cantidad, $fecha, $notas);
            $this->procesarStockYLotes($item, $cantidad, esRecepcionTotal: false);
            $this->actualizarEstadoOrden($item->ordenCompra);
        });
    }

    /**
     * Actualiza el estado de la orden según el avance de recepción de sus ítems.
     */
    public function actualizarEstadoOrden(OrdenCompra $orden): void
    {
        $orden->load('items');

        if ($orden->estaCompletamenteRecibida()) {
            $orden->update(['estado' => 'recibida']);

            return;
        }

        if ($orden->items->contains(fn (OrdenCompraItem $item): bool => $item->cantidad_recibida > 0)) {
            if (in_array($orden->estado, ['aprobada', 'recibida_parcial'], true)) {
                $orden->update(['estado' => 'recibida_parcial']);
            }
        }
    }

    private function registrarRecepcion(
        OrdenCompraItem $item,
        float $cantidad,
        Carbon $fecha,
        ?string $notas = null
    ): void {
        OrdenCompraItemRecepcion::create([
            'orden_compra_item_id' => $item->id,
            'cantidad'             => $cantidad,
            'fecha_recepcion'      => $fecha->toDateString(),
            'notas'                => $notas,
        ]);

        $item->update([
            'cantidad_recibida' => $item->cantidad_recibida + $cantidad,
        ]);

        $item->refresh();
    }

    /**
     * Procesa stock y lotes externos según la cantidad recibida.
     */
    private function procesarStockYLotes(
        OrdenCompraItem $item,
        float $cantidadRecibida,
        bool $esRecepcionTotal
    ): void {
        $item->loadMissing(['insumo', 'ordenCompra']);

        $orden = $item->ordenCompra;

        $loteExistente = LoteProcesoExterno::where('entidad_tipo', 'insumo')
            ->where('entidad_id', $item->insumo_id)
            ->where('origen_tipo', 'orden_compra')
            ->where('origen_id', $orden->id)
            ->where('estado', 'pendiente')
            ->first();

        if ($loteExistente) {
            if ($esRecepcionTotal || $item->cantidad_recibida >= $item->cantidad_solicitada) {
                $loteExistente->update(['estado' => 'en_proceso']);
            }

            return;
        }

        $plantilla = PlantillaFlujoExterno::where('entidad_tipo', 'insumo')
            ->where('entidad_id', $item->insumo_id)
            ->where('activo', true)
            ->first();

        if ($plantilla && ($esRecepcionTotal || $item->cantidad_recibida >= $item->cantidad_solicitada)) {
            $lote = LoteProcesoExterno::create([
                'entidad_tipo' => 'insumo',
                'entidad_id'   => $item->insumo_id,
                'plantilla_id' => $plantilla->id,
                'cantidad'     => $item->cantidad_solicitada,
                'origen_tipo'  => 'orden_compra',
                'origen_id'    => $orden->id,
                'estado'       => 'en_proceso',
                'fecha_inicio' => now()->toDateString(),
            ]);
            $lote->crearEtapasDesde($plantilla);

            return;
        }

        if (! $plantilla) {
            $item->insumo->increment('stock_actual', $cantidadRecibida);
        }
    }
}
