<?php

namespace App\Console\Commands;

use App\Services\StockCascoService;
use Illuminate\Console\Command;

class SimularCascoPresupuesto extends Command
{
    protected $signature = 'cascos:simular {mobiliario_id : ID del mobiliario a analizar}';

    protected $description = 'Simula (dry-run) el análisis de stock/fabricación de cascos para un mobiliario';

    public function handle(StockCascoService $stockCasco): int
    {
        $mobiliarioId = (int) $this->argument('mobiliario_id');
        $analisis = $stockCasco->analizar($mobiliarioId);

        if (! ($analisis['ok'] ?? false)) {
            $this->error($analisis['error'] ?? 'Error desconocido.');

            return self::FAILURE;
        }

        $this->info('=== Simulación de cascos (solo lectura) ===');
        $this->newLine();

        $mob = $analisis['mobiliario'];
        $this->line("<fg=cyan>Mobiliario</>");
        $this->table(
            ['ID', 'Código', 'Nombre', 'Categoría'],
            [[$mob['id'], $mob['codigo'], $mob['nombre'], $mob['categoria'] ?? '—']]
        );

        if (! empty($analisis['avisos'])) {
            foreach ($analisis['avisos'] as $aviso) {
                $this->warn("⚠ {$aviso}");
            }
            $this->newLine();
        }

        if ($analisis['plantilla']) {
            $this->line(sprintf(
                'Plantilla activa: #%d — %s',
                $analisis['plantilla']['id'],
                $analisis['plantilla']['nombre']
            ));
        }

        $this->newLine();
        $this->line('<fg=cyan>Stock y cobertura</>');
        $this->table(
            ['Stock casco', 'Lotes abiertos', 'Cobertura', 'Demanda activa', 'Desde stock', 'A fabricar'],
            [[
                $analisis['stock_casco'],
                $analisis['en_lotes_abiertos'],
                $analisis['cobertura'],
                $analisis['demanda_activa'],
                $analisis['cantidad_desde_stock'],
                $analisis['cantidad_a_fabricar'],
            ]]
        );

        $this->line(sprintf(
            'Creación de lote (si se confirmara ahora): <fg=yellow>%d</> unidades',
            $analisis['lote_a_crear']
        ));
        $this->newLine();

        $this->line('<fg=cyan>Presupuestos activos con este mobiliario</>');
        if ($analisis['presupuestos']->isEmpty()) {
            $this->line('  (ninguno)');
        } else {
            $rows = [];
            foreach ($analisis['presupuestos'] as $p) {
                $detalleItems = collect($p['items'])->map(function (array $item) {
                    $flag = $item['finalizado'] ? 'finalizado' : 'activo';

                    return "#{$item['item_id']}: {$item['cantidad']} ({$flag})";
                })->implode(', ');

                $rows[] = [
                    $p['codigo'],
                    $p['estado'],
                    $p['cantidad_total'],
                    $p['cantidad_activa'],
                    $detalleItems,
                ];
            }
            $this->table(
                ['Presupuesto', 'Estado', 'Cant. total', 'Cant. activa', 'Ítems'],
                $rows
            );
        }

        $this->newLine();
        $this->line('<fg=cyan>Lotes abiertos existentes</>');
        if ($analisis['lotes_abiertos']->isEmpty()) {
            $this->line('  (ninguno)');
        } else {
            $this->table(
                ['Código', 'Estado', 'Cantidad', 'Origen (presupuesto)'],
                $analisis['lotes_abiertos']->map(fn (array $l) => [
                    $l['codigo'],
                    $l['estado'],
                    $l['cantidad'],
                    $l['origen_codigo'] ?? ($l['origen_id'] ? "#{$l['origen_id']}" : '—'),
                ])->all()
            );
        }

        $this->newLine();
        $this->line('<fg=cyan>Insumos es_componente_casco (descuento hipotético al completar el lote)</>');
        if ($analisis['insumos_casco']->isEmpty()) {
            $this->line('  (ningún componente de casco en la BOM)');
        } else {
            $this->table(
                ['Insumo', 'Código', 'Qty unitaria', 'Total (× a fabricar)', 'Stock actual'],
                $analisis['insumos_casco']->map(fn (array $i) => [
                    $i['insumo'],
                    $i['codigo'],
                    $i['qty_unitaria'],
                    $i['total'],
                    $i['stock_actual'],
                ])->all()
            );
        }

        $this->newLine();
        $this->comment('No se crearon lotes ni se modificó stock.');

        return self::SUCCESS;
    }
}
