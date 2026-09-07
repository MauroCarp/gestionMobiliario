<?php

namespace App\Console\Commands;

use App\Models\Presupuesto;
use App\Services\StockReservaService;
use Illuminate\Console\Command;

class SimularConfirmacionPresupuesto extends Command
{
    protected $signature = 'presupuesto:simular-confirmacion {codigo : Código del presupuesto (ej. PRES-2026-0001)}';

    protected $description = 'Simula (dry-run) lotes de proceso externo y órdenes de compra que se generarían al confirmar';

    public function handle(StockReservaService $stockReserva): int
    {
        $codigo = (string) $this->argument('codigo');
        $presupuesto = Presupuesto::where('codigo', $codigo)->first();

        if (! $presupuesto) {
            $this->error("No se encontró el presupuesto {$codigo}.");

            return self::FAILURE;
        }

        $plan = $stockReserva->simularConfirmacion($presupuesto);

        $this->info("=== Simulación de confirmación (solo lectura) — {$presupuesto->codigo} ===");
        $this->line("Estado actual: {$presupuesto->estado}");
        $this->newLine();

        $this->line('<fg=cyan>Lotes de proceso externo</>');
        if (empty($plan['lotes'])) {
            $this->line('  (ninguno)');
        } else {
            $this->table(
                ['Tipo', 'Entidad', 'Cantidad', 'Origen', 'Plantilla'],
                collect($plan['lotes'])->map(fn (array $lote) => [
                    $lote['entidad_tipo'],
                    $lote['nombre'] ?? "#{$lote['entidad_id']}",
                    $lote['cantidad'],
                    $lote['origen_tipo'],
                    $lote['plantilla_id'],
                ])->all()
            );
        }

        $this->newLine();
        $this->line('<fg=cyan>Órdenes de compra</>');
        if (empty($plan['ordenes_compra'])) {
            $this->line('  (ninguna)');
        } else {
            foreach ($plan['ordenes_compra'] as $oc) {
                $accion = $oc['accion'] === 'agregar'
                    ? "Sumar a OC #{$oc['orden_compra_id']}"
                    : 'Crear nueva OC';
                $proveedor = $oc['proveedor_id'] ? "proveedor #{$oc['proveedor_id']}" : 'sin proveedor';

                $this->line("  {$accion} ({$proveedor}, prioridad {$oc['prioridad']})");
                $this->table(
                    ['Insumo', 'Cantidad'],
                    collect($oc['items'])->map(fn (array $item) => [
                        $item['nombre'] ?? "#{$item['insumo_id']}",
                        $item['cantidad'],
                    ])->all()
                );
            }
        }

        $this->newLine();
        $this->comment('No se crearon lotes ni órdenes de compra. No se modificó stock.');

        return self::SUCCESS;
    }
}
