<?php

namespace App\Console\Commands;

use App\Services\StockCascoService;
use Illuminate\Console\Command;

class SincronizarCascosPresupuestos extends Command
{
    protected $signature = 'cascos:sincronizar {--dry-run : Solo mostrar acciones sin persistir}';

    protected $description = 'Sincroniza lotes de cascos y reservas de insumos según presupuestos activos (todos los mobiliarios casco)';

    public function handle(StockCascoService $stockCasco): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('Modo dry-run: no se crearán lotes ni se recalcularán reservas.');
        }

        $this->info('=== Sincronización de cascos (todos los mobiliarios con plantilla activa) ===');
        $this->newLine();

        $sync = $stockCasco->sincronizarTodos($dryRun);
        $hadErrors = false;

        foreach ($sync['resultados'] as $resultado) {
            $analisis = $resultado['analisis'] ?? [];

            if (! ($analisis['ok'] ?? false)) {
                $this->error("Mobiliario #{$resultado['mobiliario_id']}: ".($analisis['error'] ?? 'Error'));
                $hadErrors = true;

                continue;
            }

            $mob = $analisis['mobiliario'];
            $this->line("<fg=cyan>{$mob['codigo']} — {$mob['nombre']}</> (ID {$mob['id']})");

            $this->table(
                ['Stock', 'Lotes ab.', 'Demanda', 'A fabricar'],
                [[
                    $analisis['stock_casco'],
                    $analisis['en_lotes_abiertos'],
                    $analisis['demanda_activa'],
                    $analisis['cantidad_a_fabricar'],
                ]]
            );

            if ($dryRun) {
                if ($accion = $resultado['accion_lote'] ?? null) {
                    $this->line(sprintf(
                        '  → Crearía lote: <fg=yellow>%d</> uds (origen: %s)',
                        $accion['cantidad'],
                        $accion['presupuesto'] ?? '—',
                    ));
                } else {
                    $this->line('  → No crearía lote.');
                }

                $recalc = $resultado['presupuestos_recalculados'] ?? [];
                if ($recalc !== []) {
                    $this->line('  → Recalcularía reservas en: '.implode(', ', $recalc));
                }
            } else {
                if ($lote = $resultado['lote_creado'] ?? null) {
                    $this->line(sprintf(
                        '  ✓ Lote creado: <fg=green>%s</> (%d uds)',
                        $lote['codigo'],
                        $lote['cantidad'],
                    ));
                } else {
                    $this->line('  · Sin lote nuevo.');
                }

                $recalc = $resultado['presupuestos_recalculados'] ?? [];
                if ($recalc !== []) {
                    $this->line('  ✓ Reservas recalculadas: '.implode(', ', $recalc));
                }
            }

            foreach ($resultado['errores'] ?? [] as $error) {
                $this->warn("  ⚠ {$error}");
                $hadErrors = true;
            }

            $this->newLine();
        }

        $t = $sync['totales'];
        $this->line('<fg=cyan>Resumen</>');
        $this->table(
            ['Mobiliarios', 'Lotes '.($dryRun ? 'a crear' : 'creados'), 'Presup. recalculados', 'Errores'],
            [[
                $t['mobiliarios'],
                $t['lotes_creados'],
                $t['presupuestos_recalculados'],
                $t['errores'],
            ]]
        );

        if ($dryRun) {
            $this->comment('Ejecutá sin --dry-run para aplicar cambios.');
        }

        return $hadErrors ? self::FAILURE : self::SUCCESS;
    }
}
