<?php

namespace App\Observers;

use App\Models\Presupuesto;
use App\Services\AnalisisPresupuestoService;
use App\Services\StockReservaService;
use Illuminate\Support\Facades\Log;

class PresupuestoObserver
{
    public function __construct(
        private readonly StockReservaService $stockService,
        private readonly AnalisisPresupuestoService $analisisService,
    ) {}

    public function updated(Presupuesto $presupuesto): void
    {
        if (! $presupuesto->wasChanged('estado')) {
            return;
        }

        $estadoNuevo     = $presupuesto->estado;
        $estadoAnterior  = $presupuesto->getOriginal('estado');

        try {
            match ($estadoNuevo) {
                // Al confirmar: reservar stock y generar orden de compra si hay faltantes
                'confirmado' => $this->alConfirmar($presupuesto),

                // Al pagar: consumir stock (descuento real)
                'pagado'     => $this->alPagar($presupuesto),

                // Al cancelar o rechazar: liberar reservas
                'cancelado', 'rechazado' => $this->alCancelar($presupuesto),

                default => null,
            };
        } catch (\Throwable $e) {
            Log::error("PresupuestoObserver: error en transición {$estadoAnterior} → {$estadoNuevo} para #{$presupuesto->id}: {$e->getMessage()}");
        }
    }

    private function alConfirmar(Presupuesto $presupuesto): void
    {
        $this->stockService->reservar($presupuesto);
        $this->stockService->generarOrdenCompraAutomatica($presupuesto);
    }

    private function alPagar(Presupuesto $presupuesto): void
    {
        // Si no fue confirmado antes, reservar primero
        if (! $presupuesto->reservasStock()->where('estado', 'activa')->exists()) {
            $this->stockService->reservar($presupuesto);
        }

        // El stock se descuenta al confirmar la finalización de cada ítem, no al pagar.
    }

    private function alCancelar(Presupuesto $presupuesto): void
    {
        $this->stockService->liberar($presupuesto);
    }
}
