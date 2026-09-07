<?php

namespace App\Console\Commands;

use App\Models\Insumo;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class InformePresupuestosInsumos extends Command
{
    protected $signature = 'informe:presupuestos-insumos
                            {codigos?* : Códigos de insumo (por defecto INS-0145 INS-0146)}
                            {--csv : Exportar CSV}
                            {--output= : Ruta del CSV. Use - para imprimir en consola (por defecto storage/app)}';

    protected $description = 'Lista presupuestos que contienen los insumos indicados (línea directa o BOM) y su estado de entrega';

    private const ENCABEZADOS = [
        'Insumo',
        'Nombre insumo',
        'Presupuesto',
        'Estado',
        'Agencia',
        'Ítem',
        'Origen',
        'Cant. ítem',
        'Cant. insumo',
        'Entrega',
    ];

    public function handle(): int
    {
        $codigos = collect($this->argument('codigos') ?: ['INS-0145', 'INS-0146'])
            ->map(fn (string $codigo): string => strtoupper(trim($codigo)))
            ->filter()
            ->unique()
            ->values();

        $insumos = Insumo::query()
            ->whereIn('codigo', $codigos->all())
            ->get()
            ->keyBy(fn (Insumo $insumo): string => strtoupper((string) $insumo->codigo));

        $faltantes = $codigos->diff($insumos->keys());

        if ($faltantes->isNotEmpty()) {
            $this->error('No se encontraron: '.$faltantes->implode(', '));

            return self::FAILURE;
        }

        $ids = $insumos->pluck('id')->all();

        $items = PresupuestoItem::query()
            ->where(function ($query) use ($ids): void {
                $query->whereIn('insumo_id', $ids)
                    ->orWhereHas(
                        'mobiliario.composicionTecnica',
                        fn ($q) => $q->whereIn('insumo_id', $ids),
                    );
            })
            ->with([
                'presupuesto.agencia.proyecto.marca',
                'mobiliario.composicionTecnica',
                'insumo',
            ])
            ->orderBy('presupuesto_id')
            ->orderBy('id')
            ->get();

        $filasPorInsumo = $insumos->mapWithKeys(
            fn (Insumo $insumo) => [$insumo->codigo => $this->filasDeInsumo($insumo, $items)]
        );

        if ($this->option('csv') || $this->option('output') !== null) {
            return $this->exportarCsv($filasPorInsumo);
        }

        foreach ($insumos as $insumo) {
            $this->imprimirInsumo($insumo, $filasPorInsumo[$insumo->codigo] ?? collect());
        }

        return self::SUCCESS;
    }

    /**
     * @param  Collection<int, PresupuestoItem>  $items
     * @return Collection<int, array<int, mixed>>
     */
    private function filasDeInsumo(Insumo $insumo, Collection $items): Collection
    {
        return $items
            ->map(fn (PresupuestoItem $item): ?array => $this->filaSiCorresponde($item, $insumo))
            ->filter()
            ->values();
    }

    /**
     * @param  Collection<int, array<int, mixed>>  $filas
     */
    private function imprimirInsumo(Insumo $insumo, Collection $filas): void
    {
        $this->line(sprintf(
            '<fg=cyan>%s — %s</> (%d líneas)',
            $insumo->codigo,
            $insumo->nombre,
            $filas->count(),
        ));

        if ($filas->isEmpty()) {
            $this->line('  (ningún presupuesto)');
            $this->newLine();

            return;
        }

        $this->table(
            ['Presupuesto', 'Estado', 'Agencia', 'Ítem', 'Origen', 'Cant. ítem', 'Cant. insumo', 'Entrega'],
            $filas->map(fn (array $fila): array => array_slice($fila, 2))->all(),
        );

        $pendientes = $filas->filter(fn (array $fila): bool => $fila[9] === 'Pendiente');
        $entregadas = $filas->filter(fn (array $fila): bool => $fila[9] === 'Entregado');

        $this->comment(sprintf(
            'Resumen: %d pendientes (%s uds insumo) · %d entregadas (%s uds insumo)',
            $pendientes->count(),
            $this->formatearCantidad($pendientes->sum(fn (array $fila): float => (float) $fila[8])),
            $entregadas->count(),
            $this->formatearCantidad($entregadas->sum(fn (array $fila): float => (float) $fila[8])),
        ));
        $this->newLine();
    }

    /**
     * @param  Collection<string, Collection<int, array<int, mixed>>>  $filasPorInsumo
     */
    private function exportarCsv(Collection $filasPorInsumo): int
    {
        $destino = (string) ($this->option('output') ?? '');
        $csv = $this->armarCsv($filasPorInsumo);

        if ($destino === '-') {
            $this->output->write($csv);

            return self::SUCCESS;
        }

        $ruta = $this->rutaCsv($destino);

        if (file_put_contents($ruta, $csv) === false) {
            $this->error("No se pudo escribir {$ruta}");

            return self::FAILURE;
        }

        $this->info("CSV generado: {$ruta}");

        return self::SUCCESS;
    }

    /**
     * @param  Collection<string, Collection<int, array<int, mixed>>>  $filasPorInsumo
     */
    private function armarCsv(Collection $filasPorInsumo): string
    {
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, self::ENCABEZADOS, ';');

        foreach ($filasPorInsumo as $filas) {
            foreach ($filas as $fila) {
                fputcsv($handle, $fila, ';');
            }
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    private function rutaCsv(string $destino): string
    {
        if ($destino !== '') {
            return str_contains($destino, DIRECTORY_SEPARATOR) || str_contains($destino, '/')
                ? $destino
                : storage_path('app/'.$destino);
        }

        return storage_path('app/informe-presupuestos-insumos-'.now()->format('Y-m-d-His').'.csv');
    }

    /**
     * @return array{0:string,1:string,2:string,3:string,4:string,5:string,6:string,7:int|float,8:int|float,9:string}|null
     */
    private function filaSiCorresponde(PresupuestoItem $item, Insumo $insumo): ?array
    {
        $origen = null;
        $cantidadInsumo = 0.0;

        if ((int) $item->insumo_id === (int) $insumo->id) {
            $origen = 'Directo';
            $cantidadInsumo = (float) $item->cantidad;
        } else {
            $qtyBom = (float) ($item->mobiliario?->composicionTecnica
                ?->where('insumo_id', $insumo->id)
                ->sum('cantidad') ?? 0);

            if ($qtyBom <= 0) {
                return null;
            }

            $origen = 'BOM';
            $cantidadInsumo = $qtyBom * (float) $item->cantidad;
        }

        $presupuesto = $item->presupuesto;

        return [
            $insumo->codigo,
            $insumo->nombre,
            $presupuesto?->codigo ?? "#{$item->presupuesto_id}",
            Presupuesto::ESTADOS[$presupuesto?->estado] ?? ($presupuesto?->estado ?? '—'),
            $presupuesto?->agencia?->nombre ?? '—',
            $item->item_codigo !== '—'
                ? "[{$item->item_codigo}] {$item->item_nombre}"
                : $item->item_nombre,
            $origen,
            $this->formatearCantidad((float) $item->cantidad),
            $this->formatearCantidad($cantidadInsumo),
            $item->estaEntregado() ? 'Entregado' : 'Pendiente',
        ];
    }

    private function formatearCantidad(float $cantidad): int|float|string
    {
        return fmod($cantidad, 1.0) === 0.0 ? (int) $cantidad : round($cantidad, 4);
    }
}
