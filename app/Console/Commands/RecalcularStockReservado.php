<?php

namespace App\Console\Commands;

use App\Models\Insumo;
use App\Models\OrdenProduccion;
use App\Models\Presupuesto;
use App\Models\ReservaStock;
use App\Services\OrdenProduccionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class RecalcularStockReservado extends Command
{
    protected $signature = 'stock:recalcular-reservado {--dry-run : Solo mostrar sin persistir}';

    protected $description = 'Recalcula reservas_stock desde presupuestos activos y órdenes de producción iniciadas';

    private const ESTADOS_ACTIVOS = ['confirmado', 'pagado', 'entregado_parcial'];

    private const ESTADOS_OP_INICIADAS = ['en_proceso', 'pausada'];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('Modo dry-run: no se persistirán cambios.');
        }

        $reservasAnteriores = ReservaStock::count();

        $presupuestos = Presupuesto::query()
            ->whereIn('estado', self::ESTADOS_ACTIVOS)
            ->with(['items' => fn ($q) => $q
                ->whereNull('finalizado_at')
                ->pendienteEntrega()
                ->with('mobiliario.composicionTecnica')])
            ->get();

        $ordenes = OrdenProduccion::query()
            ->whereIn('estado', self::ESTADOS_OP_INICIADAS)
            ->with(['items' => fn ($q) => $q
                ->whereNull('finalizado_at')
                ->with('insumos')])
            ->get();

        $itemsConsiderados = 0;
        $itemsOpConsiderados = 0;
        $filasCreadas = 0;
        $totalesPorInsumo = [];
        $opService = app(OrdenProduccionService::class);

        DB::beginTransaction();

        try {
            ReservaStock::query()->delete();

            foreach ($presupuestos as $presupuesto) {
                $demandaPresupuesto = [];

                foreach ($presupuesto->items as $item) {
                    $itemsConsiderados++;

                    foreach ($item->demandaInsumos() as $insumoId => $cantidad) {
                        if ($cantidad <= 0) {
                            continue;
                        }

                        $demandaPresupuesto[$insumoId] = ($demandaPresupuesto[$insumoId] ?? 0) + $cantidad;
                    }
                }

                foreach ($demandaPresupuesto as $insumoId => $cantidad) {
                    if ($cantidad <= 0) {
                        continue;
                    }

                    ReservaStock::create([
                        'presupuesto_id' => $presupuesto->id,
                        'insumo_id' => $insumoId,
                        'cantidad_reservada' => $cantidad,
                        'estado' => 'activa',
                    ]);

                    $filasCreadas++;
                    $totalesPorInsumo[$insumoId] = ($totalesPorInsumo[$insumoId] ?? 0) + $cantidad;
                }
            }

            foreach ($ordenes as $orden) {
                $itemsOpConsiderados += $orden->items->count();
                $demandaOrden = $opService->calcularDemandaPendiente($orden);

                foreach ($demandaOrden as $insumoId => $cantidad) {
                    if ($cantidad <= 0) {
                        continue;
                    }

                    ReservaStock::create([
                        'orden_produccion_id' => $orden->id,
                        'presupuesto_id' => null,
                        'insumo_id' => $insumoId,
                        'cantidad_reservada' => $cantidad,
                        'estado' => 'activa',
                    ]);

                    $filasCreadas++;
                    $totalesPorInsumo[$insumoId] = ($totalesPorInsumo[$insumoId] ?? 0) + $cantidad;
                }
            }

            if ($dryRun) {
                DB::rollBack();
                $this->info('Dry-run completado (cambios revertidos).');
            } else {
                DB::commit();
                $this->info('Reservas recalculadas correctamente.');
            }
        } catch (Throwable $e) {
            DB::rollBack();

            $this->error('Error al recalcular: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Resumen:');
        $this->line("  Reservas anteriores eliminadas: {$reservasAnteriores}");
        $this->line('  Presupuestos procesados: '.$presupuestos->count());
        $this->line("  Items de presupuesto considerados: {$itemsConsiderados}");
        $this->line('  Órdenes de producción procesadas: '.$ordenes->count());
        $this->line("  Items de OP considerados: {$itemsOpConsiderados}");
        $this->line("  Filas de reserva creadas: {$filasCreadas}");

        $this->mostrarTotalesPorInsumo($totalesPorInsumo);

        return self::SUCCESS;
    }

    /**
     * @param  array<int, float>  $totalesPorInsumo
     */
    private function mostrarTotalesPorInsumo(array $totalesPorInsumo): void
    {
        if ($totalesPorInsumo === []) {
            $this->line('  No se generaron reservas (sin demanda pendiente).');

            return;
        }

        $insumos = Insumo::query()
            ->whereIn('id', array_keys($totalesPorInsumo))
            ->orderBy('codigo')
            ->get()
            ->keyBy('id');

        $rows = [];

        foreach ($totalesPorInsumo as $insumoId => $total) {
            $insumo = $insumos->get($insumoId);

            $rows[] = [
                $insumo?->codigo ?? (string) $insumoId,
                $insumo?->nombre ?? '—',
                number_format($total, 2, '.', ''),
            ];
        }

        usort($rows, fn (array $a, array $b): int => strcmp($a[0], $b[0]));

        $this->newLine();
        $this->table(['Código', 'Insumo', 'Total reservado'], $rows);
    }
}
