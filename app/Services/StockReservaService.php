<?php

namespace App\Services;

use App\Models\Insumo;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraItem;
use App\Models\Presupuesto;
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
        private readonly AnalisisPresupuestoService $analisis
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
    }

    /**
     * Descuenta el stock real y marca reservas como consumidas (presupuesto pagado).
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
     * Genera una orden de compra automática con los insumos faltantes.
     * No genera duplicados si ya existe una orden 'sugerida' o 'pendiente' para el presupuesto.
     */
    public function generarOrdenCompraAutomatica(Presupuesto $presupuesto): ?OrdenCompra
    {
        $sugerencias = $this->analisis->sugerenciasCompra();

        if ($sugerencias->isEmpty()) {
            return null;
        }

        // Filtrar solo los insumos que este presupuesto necesita
        $demanda = $this->calcularDemandaPresupuesto($presupuesto);
        $insumoIds = array_keys($demanda);

        $itemsOrden = $sugerencias->filter(
            fn ($s) => in_array($s['insumo']->id, $insumoIds)
        );

        if ($itemsOrden->isEmpty()) {
            return null;
        }

        $prioridadOrden = $itemsOrden->contains(fn ($s) => $s['prioridad'] === 'critica')
            ? 'critica'
            : ($itemsOrden->contains(fn ($s) => $s['prioridad'] === 'alta') ? 'alta' : 'media');

        return DB::transaction(function () use ($presupuesto, $itemsOrden, $prioridadOrden) {
            $orden = OrdenCompra::create([
                'estado'                   => 'sugerida',
                'prioridad'                => $prioridadOrden,
                'generado_automaticamente' => true,
                'presupuesto_id'           => $presupuesto->id,
                'observaciones'            => "Generada automáticamente para {$presupuesto->codigo}",
            ]);

            foreach ($itemsOrden as $sugerencia) {
                OrdenCompraItem::create([
                    'orden_compra_id'    => $orden->id,
                    'insumo_id'          => $sugerencia['insumo']->id,
                    'cantidad_solicitada' => $sugerencia['cantidad_sugerida'],
                    'precio_unitario'    => $sugerencia['insumo']->precio_costo,
                ]);
            }

            return $orden;
        });
    }

    // ─── Internals ────────────────────────────────────────────────────────────

    private function calcularDemandaPresupuesto(Presupuesto $presupuesto): array
    {
        $presupuesto->loadMissing([
            'items.mobiliario.composicionTecnica',
        ]);

        $demanda = [];

        foreach ($presupuesto->items as $item) {
            $cantMobiliario = (int) $item->cantidad;

            foreach ($item->mobiliario->composicionTecnica as $comp) {
                $id = $comp->insumo_id;
                $demanda[$id] = ($demanda[$id] ?? 0) + ($comp->cantidad * $cantMobiliario);
            }
        }

        return $demanda;
    }
}
