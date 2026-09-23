<?php

namespace App\Console\Commands;

use App\Models\Insumo;
use App\Models\PresupuestoItem;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class InformePendientesEntregaInsumo extends Command
{
    protected $signature = 'informe:pendientes-entrega {codigo=INS-0062 : Código del insumo a cruzar con la composición técnica}';

    protected $description = 'Lista mobiliarios pendientes de entrega agrupados según si su composición técnica incluye un insumo';

    private const ESTADOS_PENDIENTES_ENTREGA = ['confirmado', 'pagado', 'entregado_parcial'];

    public function handle(): int
    {
        $codigo = (string) $this->argument('codigo');

        $insumo = Insumo::query()->where('codigo', $codigo)->first();

        if (! $insumo) {
            $this->error("No se encontró el insumo con código {$codigo}.");

            return self::FAILURE;
        }

        $items = PresupuestoItem::query()
            ->whereNotNull('mobiliario_id')
            ->pendienteEntrega()
            ->whereHas(
                'presupuesto',
                fn ($query) => $query->where('finalizado_at', NULL),
            )
            ->with([
                'mobiliario.composicionTecnica',
                'presupuesto.agencia.proyecto.marca',
            ])
            ->orderBy('id')
            ->get();

        [$conInsumo, $sinInsumo] = $items->partition(
            fn (PresupuestoItem $item): bool => (bool) $item->mobiliario
                ?->composicionTecnica
                ?->contains('insumo_id', $insumo->id),
        );

        // #region agent log
        $ch15Items = $items->filter(fn (PresupuestoItem $item): bool => ($item->mobiliario?->codigo_interno ?? '') === 'CH15');
        $ch15Logs = $ch15Items->map(function (PresupuestoItem $item) use ($insumo): array {
            $bom = $item->mobiliario?->composicionTecnica ?? collect();
            $bomInsumo = $bom->where('insumo_id', $insumo->id)->values();

            return [
                'item_id' => $item->id,
                'item_cantidad' => $item->cantidad,
                'mobiliario_id' => $item->mobiliario_id,
                'version_actual' => $item->mobiliario?->version_actual,
                'agencia' => $item->presupuesto?->agencia?->nombre,
                'marca' => $item->presupuesto?->agencia?->proyecto?->marca?->nombre,
                'bom_rows_total' => $bom->count(),
                'bom_insumo_row_count' => $bomInsumo->count(),
                'bom_insumo_cantidad_sum' => $bomInsumo->sum('cantidad'),
                'bom_insumo_rows' => $bomInsumo->map(fn ($row): array => [
                    'id' => $row->id,
                    'cantidad' => $row->cantidad,
                    'version' => $row->version,
                    'activo' => $row->activo,
                ])->all(),
            ];
        })->values()->all();
        file_put_contents(base_path('debug-379ff4.log'), json_encode([
            'sessionId' => '379ff4',
            'runId' => 'run1',
            'hypothesisId' => 'A,B,C,D,E',
            'location' => 'InformePendientesEntregaInsumo.php:partition',
            'message' => 'CH15 pending items vs BOM for insumo',
            'data' => [
                'insumo_id' => $insumo->id,
                'insumo_codigo' => $insumo->codigo,
                'ch15_item_count' => $ch15Items->count(),
                'ch15_in_con' => $conInsumo->filter(fn (PresupuestoItem $item): bool => ($item->mobiliario?->codigo_interno ?? '') === 'CH15')->count(),
                'ch15_in_sin' => $sinInsumo->filter(fn (PresupuestoItem $item): bool => ($item->mobiliario?->codigo_interno ?? '') === 'CH15')->count(),
                'items' => $ch15Logs,
            ],
            'timestamp' => (int) (microtime(true) * 1000),
        ], JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND);
        // #endregion

        $this->info("Insumo: {$insumo->codigo} — {$insumo->nombre}");
        $this->newLine();

        $this->imprimirGrupo("Con {$insumo->codigo} en composición técnica", $conInsumo, $insumo);
        $this->imprimirGrupo("Sin {$insumo->codigo} en composición técnica", $sinInsumo, $insumo);

        return self::SUCCESS;
    }

    /**
     * @param  Collection<int, PresupuestoItem>  $items
     */
    private function imprimirGrupo(string $titulo, Collection $items, Insumo $insumo): void
    {
        $cantidad = (int) $items->sum(fn (PresupuestoItem $item): int => $item->cantidadPendiente());
        $this->line("<fg=cyan>{$titulo} ({$items->count()} pendientes, {$cantidad} uds)</>");

        if ($items->isEmpty()) {
            $this->line('  (ninguno)');
            $this->newLine();

            return;
        }

        $rows = $items->map(function (PresupuestoItem $item) use ($insumo): array {
            $mobiliario = $item->mobiliario;
            $label = $mobiliario
                ? "[{$mobiliario->codigo_interno}] {$mobiliario->nombre}"
                : '—';
            $cantInsumo = $this->cantidadInsumoEnBom($item, $insumo->id);

            // #region agent log
            if (($mobiliario?->codigo_interno ?? '') === 'CH15') {
                file_put_contents(base_path('debug-379ff4.log'), json_encode([
                    'sessionId' => '379ff4',
                    'runId' => 'post-fix',
                    'hypothesisId' => 'A,B',
                    'location' => 'InformePendientesEntregaInsumo.php:imprimirGrupo',
                    'message' => 'CH15 row quantities after adding BOM insumo column',
                    'data' => [
                        'item_cantidad' => $item->cantidad,
                        'cant_insumo_bom' => $cantInsumo,
                    ],
                    'timestamp' => (int) (microtime(true) * 1000),
                ], JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND);
            }
            // #endregion

            return [
                $label,
                $item->presupuesto?->agencia?->proyecto?->marca?->nombre ?? '—',
                $item->presupuesto?->agencia?->nombre ?? '—',
                $item->cantidadPendiente(),
                $cantInsumo,
            ];
        })->all();

        $this->table(['Mobiliario', 'Marca', 'Agencia', 'Cantidad', 'Cant. insumo'], $rows);
        $this->newLine();
    }

    private function cantidadInsumoEnBom(PresupuestoItem $item, int $insumoId): int|float
    {
        $sum = (float) ($item->mobiliario?->composicionTecnica
            ?->where('insumo_id', $insumoId)
            ->sum('cantidad') ?? 0);

        return fmod($sum, 1.0) === 0.0 ? (int) $sum : $sum;
    }
}
