<?php

namespace App\Observers;

use App\Models\Presupuesto;
use App\Services\AnalisisPresupuestoService;
use App\Services\PresupuestoConfirmadoToastService;
use App\Services\PresupuestoItemProduccionService;
use App\Services\StockMobiliarioService;
use App\Services\StockReservaService;
use Illuminate\Support\Facades\Log;

class PresupuestoObserver
{
    public function __construct(
        private readonly StockReservaService $stockService,
        private readonly StockMobiliarioService $stockMobiliarioService,
        private readonly AnalisisPresupuestoService $analisisService,
        private readonly PresupuestoItemProduccionService $produccionService,
        private readonly PresupuestoConfirmadoToastService $confirmadoToastService,
    ) {}

    public function updated(Presupuesto $presupuesto): void
    {
        if (! $presupuesto->wasChanged('estado')) {
            return;
        }

        $estadoNuevo     = $presupuesto->estado;
        $estadoAnterior  = $presupuesto->getOriginal('estado');

        if (
            $estadoNuevo === 'confirmado'
            && ! in_array($estadoAnterior, ['pagado', 'entregado_parcial', 'entregado'], true)
        ) {
            try {
                $this->confirmadoToastService->publicar($presupuesto);
            } catch (\Throwable $e) {
                Log::warning(
                    "PresupuestoObserver: no se pudo notificar confirmación de #{$presupuesto->id}: {$e->getMessage()}",
                    ['exception' => $e],
                );
            }
        }

        try {
            match ($estadoNuevo) {
                // Al confirmar: reservar stock, lotes de proceso externo y OC si hay faltantes
                'confirmado' => $this->alConfirmar($presupuesto),

                // Al pagar: consumir stock (descuento real)
                'pagado'     => $this->alPagar($presupuesto),

                // Al cancelar o rechazar: liberar reservas
                'cancelado', 'rechazado' => $this->alCancelar($presupuesto),

                default => null,
            };
        } catch (\Throwable $e) {
            Log::error(
                "PresupuestoObserver: error en transición {$estadoAnterior} → {$estadoNuevo} para #{$presupuesto->id}: {$e->getMessage()}",
                ['exception' => $e],
            );
        }
    }

    private function alConfirmar(Presupuesto $presupuesto): void
    {
        $this->stockMobiliarioService->asignarStockPresupuesto($presupuesto);
        $this->stockService->aplicarEfectosConfirmacion($presupuesto);
        $this->produccionService->crearEtapasParaPresupuesto($presupuesto);
    }

    private function alPagar(Presupuesto $presupuesto): void
    {
        if (! $presupuesto->items()->whereNotNull('stock_descontado_at')->exists()) {
            $this->stockMobiliarioService->asignarStockPresupuesto($presupuesto);
        }

        if (! $presupuesto->reservasStock()->where('estado', 'activa')->exists()) {
            $this->stockService->reservar($presupuesto);
        }

        // El stock se descuenta al confirmar la finalización de cada ítem, no al pagar.
    }

    private function alCancelar(Presupuesto $presupuesto): void
    {
        $this->stockMobiliarioService->reintegrarStockPresupuesto($presupuesto);
        $this->stockService->liberar($presupuesto);
    }
}
