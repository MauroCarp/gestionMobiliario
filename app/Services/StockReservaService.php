<?php

namespace App\Services;

use App\Models\Insumo;
use App\Models\LoteProcesoExterno;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraItem;
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

        $demanda = $item->demandaInsumos();

        DB::transaction(function () use ($item, $demanda) {
            foreach ($demanda as $insumoId => $cantidad) {
                Insumo::where('id', $insumoId)
                    ->decrement('stock_actual', $cantidad);

                $reserva = ReservaStock::where('presupuesto_id', $item->presupuesto_id)
                    ->where('insumo_id', $insumoId)
                    ->where('estado', 'activa')
                    ->lockForUpdate()
                    ->first();

                if (! $reserva) {
                    continue;
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

            $item->update([
                'finalizado_at'    => now(),
                'finalizado_por'   => auth()->id(),
            ]);
        });
    }

    /**
     * Genera órdenes de compra automáticas agrupadas por proveedor.
     * Si ya existe una OC sugerida/pendiente para el proveedor, agrega los ítems allí.
     * Si el insumo tiene un flujo externo activo, crea también el LoteProcesoExterno.
     * Para mobiliarios del presupuesto con flujo externo activo, crea lotes directamente.
     *
     * @return array<int, OrdenCompra>
     */
    public function generarOrdenCompraAutomatica(Presupuesto $presupuesto): array
    {
        if (OrdenCompra::where('presupuesto_id', $presupuesto->id)
            ->where('generado_automaticamente', true)
            ->exists()) {
            $this->crearLotesExternosMobiliarios($presupuesto);

            return [];
        }

        $demanda = $this->calcularDemandaPresupuesto($presupuesto);

        if (empty($demanda)) {
            $this->crearLotesExternosMobiliarios($presupuesto);

            return [];
        }

        $insumos = Insumo::whereIn('id', array_keys($demanda))
            ->with(['plantillaFlujos' => fn ($q) => $q->where('activo', true)->with('etapas')])
            ->get()
            ->keyBy('id');

        $insumosLowStock = $insumos->filter(
            fn (Insumo $insumo) => $insumo->stock_actual < $insumo->stock_minimo
        );

        $ordenes = [];

        if ($insumosLowStock->isNotEmpty()) {
            $porProveedor = $insumosLowStock->groupBy(fn (Insumo $insumo) => $insumo->proveedor_id ?? 'sin_proveedor');

            foreach ($porProveedor as $proveedorKey => $insumosGrupo) {
                $proveedorId = $proveedorKey === 'sin_proveedor' ? null : (int) $proveedorKey;

                $esCritico = $insumosGrupo->contains(fn (Insumo $i) => ($i->stock_disponible ?? 0) <= 0);
                $prioridad = $esCritico ? 'critica' : 'alta';

                $orden = DB::transaction(function () use ($presupuesto, $insumosGrupo, $demanda, $prioridad, $proveedorId) {
                    $oc = OrdenCompra::where('proveedor_id', $proveedorId)
                        ->whereIn('estado', ['sugerida', 'pendiente'])
                        ->first();

                    if (! $oc) {
                        $oc = OrdenCompra::create([
                            'estado'                   => 'sugerida',
                            'prioridad'                => $prioridad,
                            'generado_automaticamente' => true,
                            'presupuesto_id'           => $presupuesto->id,
                            'proveedor_id'             => $proveedorId,
                            'observaciones'            => "Generada automáticamente para presupuesto {$presupuesto->codigo}",
                        ]);
                    } elseif ($oc->prioridad !== 'critica' && $prioridad === 'critica') {
                        $oc->update(['prioridad' => 'critica']);
                    }

                    foreach ($insumosGrupo as $insumo) {
                        $cantidad = $demanda[$insumo->id];

                        $itemExistente = OrdenCompraItem::where('orden_compra_id', $oc->id)
                            ->where('insumo_id', $insumo->id)
                            ->first();

                        if ($itemExistente) {
                            $itemExistente->update([
                                'cantidad_solicitada' => $itemExistente->cantidad_solicitada + $cantidad,
                                'precio_unitario'     => $insumo->precio_costo,
                            ]);
                        } else {
                            OrdenCompraItem::create([
                                'orden_compra_id'     => $oc->id,
                                'insumo_id'           => $insumo->id,
                                'cantidad_solicitada' => $cantidad,
                                'precio_unitario'     => $insumo->precio_costo,
                            ]);
                        }

                        $this->crearLotePendienteSiCorresponde($insumo, $cantidad, $oc, $presupuesto);
                    }

                    return $oc;
                });

                $ordenes[] = $orden;
            }
        }

        $this->crearLotesExternosMobiliarios($presupuesto);

        return $ordenes;
    }

    private function crearLotePendienteSiCorresponde(
        Insumo $insumo,
        float $cantidad,
        OrdenCompra $oc,
        Presupuesto $presupuesto
    ): void {
        $plantilla = $insumo->plantillaFlujos->first();

        if (! $plantilla) {
            return;
        }

        $yaExiste = LoteProcesoExterno::where('entidad_tipo', 'insumo')
            ->where('entidad_id', $insumo->id)
            ->where('origen_tipo', 'orden_compra')
            ->where('origen_id', $oc->id)
            ->whereNotIn('estado', ['cancelado'])
            ->exists();

        if ($yaExiste) {
            return;
        }

        $lote = LoteProcesoExterno::create([
            'plantilla_id'  => $plantilla->id,
            'entidad_tipo'  => 'insumo',
            'entidad_id'    => $insumo->id,
            'cantidad'      => $cantidad,
            'origen_tipo'   => 'orden_compra',
            'origen_id'     => $oc->id,
            'estado'        => 'pendiente',
            'fecha_inicio'  => now()->toDateString(),
            'observaciones' => "Generado al confirmar {$presupuesto->codigo}. Activar al recibir la OC.",
        ]);
        $lote->crearEtapasDesde($plantilla);
    }

    /**
     * Para cada mobiliario del presupuesto que tenga una PlantillaFlujoExterno activa,
     * crea un LoteProcesoExterno en estado pendiente.
     * Idempotente: no duplica si ya existe un lote activo para el mismo mobiliario/presupuesto.
     */
    private function crearLotesExternosMobiliarios(Presupuesto $presupuesto): void
    {
        $presupuesto->loadMissing(['items.mobiliario']);

        foreach ($presupuesto->items as $item) {
            // Los ítems de silla (insumo directo) no tienen flujo externo de fabricación
            if (!$item->mobiliario_id) {
                continue;
            }

            // Evitar duplicados usando origen_id para trazar el lote al presupuesto
            $yaExiste = LoteProcesoExterno::where('entidad_tipo', 'mobiliario')
                ->where('entidad_id', $item->mobiliario_id)
                ->where('origen_id', $presupuesto->id)
                ->whereNotIn('estado', ['cancelado'])
                ->exists();

            if ($yaExiste) {
                continue;
            }

            $plantilla = $item->mobiliario
                ->plantillaFlujos()
                ->where('activo', true)
                ->with('etapas')
                ->first();

            if (! $plantilla) {
                continue;
            }

            $lote = LoteProcesoExterno::create([
                'plantilla_id'  => $plantilla->id,
                'entidad_tipo'  => 'mobiliario',
                'entidad_id'    => $item->mobiliario_id,
                'cantidad'      => $item->cantidad,
                'origen_tipo'   => 'manual',
                'origen_id'     => $presupuesto->id,
                'estado'        => 'pendiente',
                'fecha_inicio'  => now()->toDateString(),
                'observaciones' => "Generado al confirmar presupuesto {$presupuesto->codigo} - {$presupuesto->agencia} - {$presupuesto->agencia->proyecto->marca}",
            ]);

            $lote->crearEtapasDesde($plantilla);
        }
    }

    // ─── Internals ────────────────────────────────────────────────────────────

    private function calcularDemandaPresupuesto(Presupuesto $presupuesto): array
    {
        $presupuesto->loadMissing([
            'items.mobiliario.composicionTecnica',
            'items.insumo',
        ]);

        $demanda = [];

        foreach ($presupuesto->items as $item) {
            $cantItem = (int) $item->cantidad;

            // Ítem de silla (insumo directo) → la demanda ES el insumo × cantidad
            if ($item->insumo_id) {
                $demanda[$item->insumo_id] = ($demanda[$item->insumo_id] ?? 0) + $cantItem;
                continue;
            }

            foreach ($item->mobiliario->composicionTecnica as $comp) {
                $id = $comp->insumo_id;
                $demanda[$id] = ($demanda[$id] ?? 0) + ($comp->cantidad * $cantItem);
            }
        }

        return $demanda;
    }
}
