<?php

namespace App\Console\Commands;

use App\Models\Insumo;
use App\Models\OrdenProduccion;
use App\Models\OrdenProduccionItem;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Models\ReservaStock;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class VerificarReservaInsumo extends Command
{
    protected $signature = 'stock:verificar-reserva {codigo : Código del insumo a verificar}';

    protected $description = 'Lista mobiliarios no finalizados que usan un insumo y compara su demanda con las reservas activas';

    private const ESTADOS_ACTIVOS = ['confirmado', 'pagado', 'entregado_parcial'];

    private const ESTADOS_OP_INICIADAS = ['en_proceso', 'pausada'];

    public function handle(): int
    {
        $codigo = strtoupper(trim((string) $this->argument('codigo')));

        $insumo = Insumo::query()->where('codigo', $codigo)->first();

        if (! $insumo) {
            $this->error("No se encontró el insumo con código {$codigo}.");

            return self::FAILURE;
        }

        $items = PresupuestoItem::query()
            ->whereNotNull('mobiliario_id')
            ->whereNull('finalizado_at')
            ->pendienteEntrega()
            ->whereHas(
                'presupuesto',
                fn ($query) => $query->whereIn('estado', self::ESTADOS_ACTIVOS),
            )
            ->whereHas(
                'mobiliario.composicionTecnica',
                fn ($query) => $query
                    ->where('insumo_id', $insumo->id)
                    ->where('es_componente_casco', false),
            )
            ->with([
                'presupuesto',
                'mobiliario.composicionTecnica',
            ])
            ->orderBy('presupuesto_id')
            ->orderBy('id')
            ->get();

        $itemsOp = OrdenProduccionItem::query()
            ->whereNull('finalizado_at')
            ->whereHas(
                'ordenProduccion',
                fn ($query) => $query->whereIn('estado', self::ESTADOS_OP_INICIADAS),
            )
            ->whereHas(
                'insumos',
                fn ($query) => $query->where('insumo_id', $insumo->id),
            )
            ->with([
                'ordenProduccion',
                'mobiliario',
                'insumos',
            ])
            ->orderBy('orden_produccion_id')
            ->orderBy('id')
            ->get();

        $this->info("Insumo: {$insumo->codigo} — {$insumo->nombre}");
        $this->newLine();

        $this->imprimirLineas($items, $insumo);
        $this->compararPorPresupuesto($items, $insumo);
        $this->imprimirLineasOp($itemsOp, $insumo);
        $this->compararPorOrden($itemsOp, $insumo);
        $this->imprimirTotales($items, $itemsOp, $insumo);

        return self::SUCCESS;
    }

    /**
     * @param  Collection<int, PresupuestoItem>  $items
     */
    private function imprimirLineas(Collection $items, Insumo $insumo): void
    {
        $this->line('<fg=cyan>Presupuestos: mobiliarios no finalizados que reservan este insumo</>');

        if ($items->isEmpty()) {
            $this->line('  (ninguno)');
            $this->newLine();

            return;
        }

        $rows = $items->map(function (PresupuestoItem $item) use ($insumo): array {
            $mobiliario = $item->mobiliario;
            $cantBom = $this->cantidadInsumoEnBom($item, $insumo->id);
            $aFabricar = $item->cantidadParaFabricacion();
            $demanda = (float) ($item->demandaInsumos()[$insumo->id] ?? 0);

            return [
                $item->presupuesto?->codigo ?? '—',
                $item->presupuesto?->estado ?? '—',
                $mobiliario
                    ? "[{$mobiliario->codigo_interno}] {$mobiliario->nombre}"
                    : '—',
                $item->cantidad,
                $aFabricar,
                $this->formatoCantidad($cantBom),
                $this->formatoCantidad($demanda),
            ];
        })->all();

        $this->table(
            ['Presupuesto', 'Estado', 'Mobiliario', 'Cant. ítem', 'A fabricar', 'Cant. BOM', 'Demanda'],
            $rows,
        );
        $this->newLine();
    }

    /**
     * @param  Collection<int, PresupuestoItem>  $items
     */
    private function compararPorPresupuesto(Collection $items, Insumo $insumo): void
    {
        $this->line('<fg=cyan>Reserva por presupuesto</>');

        $reservas = ReservaStock::query()
            ->activa()
            ->where('insumo_id', $insumo->id)
            ->whereNotNull('presupuesto_id')
            ->get()
            ->groupBy('presupuesto_id');

        $demandaPorPresupuesto = $items
            ->groupBy('presupuesto_id')
            ->map(fn (Collection $grupo): float => $grupo->sum(
                fn (PresupuestoItem $item): float => (float) ($item->demandaInsumos()[$insumo->id] ?? 0),
            ));

        $presupuestoIds = $demandaPorPresupuesto->keys()
            ->merge($reservas->keys())
            ->unique()
            ->sort()
            ->values();

        if ($presupuestoIds->isEmpty()) {
            $this->line('  (ninguno)');
            $this->newLine();

            return;
        }

        $codigos = Presupuesto::query()
            ->whereIn('id', $presupuestoIds)
            ->pluck('codigo', 'id');

        $huboReservaMayor = false;
        $rows = [];

        foreach ($presupuestoIds as $presupuestoId) {
            $demanda = (float) $demandaPorPresupuesto->get($presupuestoId, 0);
            $reservado = (float) ($reservas->get($presupuestoId)?->sum('cantidad_reservada') ?? 0);
            $coincide = abs($demanda - $reservado) < 0.01;

            if ($reservado > $demanda + 0.01) {
                $huboReservaMayor = true;
            }

            $rows[] = [
                $codigos->get($presupuestoId) ?? (string) $presupuestoId,
                $this->formatoCantidad($demanda),
                $this->formatoCantidad($reservado),
                $coincide ? 'OK' : 'NO COINCIDE',
            ];
        }

        $this->table(['Presupuesto', 'Demanda mobiliarios', 'Reservado', 'Resultado'], $rows);

        if ($huboReservaMayor) {
            $this->warn('Si la reserva del presupuesto es mayor, puede haber otras líneas (silla/insumo directo o casco) que también reservan este insumo. No se listan como mobiliario.');
        }

        $this->newLine();
    }

    /**
     * @param  Collection<int, OrdenProduccionItem>  $items
     */
    private function imprimirLineasOp(Collection $items, Insumo $insumo): void
    {
        $this->line('<fg=cyan>Órdenes de producción iniciadas: mobiliarios no finalizados</>');

        if ($items->isEmpty()) {
            $this->line('  (ninguno)');
            $this->newLine();

            return;
        }

        $rows = $items->map(function (OrdenProduccionItem $item) use ($insumo): array {
            $mobiliario = $item->mobiliario;
            $snapshot = $item->insumos->firstWhere('insumo_id', $insumo->id);
            $demanda = $snapshot?->cantidadPendiente() ?? 0;

            return [
                $item->ordenProduccion?->codigo ?? '—',
                $item->ordenProduccion?->estado ?? '—',
                $mobiliario
                    ? "[{$mobiliario->codigo_interno}] {$mobiliario->nombre}"
                    : '—',
                $item->cantidad,
                $item->cantidadPendiente(),
                $this->formatoCantidad((float) ($snapshot?->cantidad_unitaria ?? 0)),
                $this->formatoCantidad((float) $demanda),
            ];
        })->all();

        $this->table(
            ['Orden', 'Estado', 'Mobiliario', 'Cant. ítem', 'Pendiente', 'Cant. BOM', 'Demanda'],
            $rows,
        );
        $this->newLine();
    }

    /**
     * @param  Collection<int, OrdenProduccionItem>  $items
     */
    private function compararPorOrden(Collection $items, Insumo $insumo): void
    {
        $this->line('<fg=cyan>Reserva por orden de producción</>');

        $reservas = ReservaStock::query()
            ->activa()
            ->where('insumo_id', $insumo->id)
            ->whereNotNull('orden_produccion_id')
            ->get()
            ->groupBy('orden_produccion_id');

        $demandaPorOrden = $items
            ->groupBy('orden_produccion_id')
            ->map(fn (Collection $grupo): float => $grupo->sum(
                fn (OrdenProduccionItem $item): float => (float) (
                    $item->insumos->firstWhere('insumo_id', $insumo->id)?->cantidadPendiente() ?? 0
                ),
            ));

        $ordenIds = $demandaPorOrden->keys()
            ->merge($reservas->keys())
            ->unique()
            ->sort()
            ->values();

        if ($ordenIds->isEmpty()) {
            $this->line('  (ninguno)');
            $this->newLine();

            return;
        }

        $codigos = OrdenProduccion::query()
            ->whereIn('id', $ordenIds)
            ->pluck('codigo', 'id');

        $rows = [];

        foreach ($ordenIds as $ordenId) {
            $demanda = (float) $demandaPorOrden->get($ordenId, 0);
            $reservado = (float) ($reservas->get($ordenId)?->sum('cantidad_reservada') ?? 0);
            $coincide = abs($demanda - $reservado) < 0.01;

            $rows[] = [
                $codigos->get($ordenId) ?? (string) $ordenId,
                $this->formatoCantidad($demanda),
                $this->formatoCantidad($reservado),
                $coincide ? 'OK' : 'NO COINCIDE',
            ];
        }

        $this->table(['Orden', 'Demanda mobiliarios', 'Reservado', 'Resultado'], $rows);
        $this->newLine();
    }

    /**
     * @param  Collection<int, PresupuestoItem>  $items
     * @param  Collection<int, OrdenProduccionItem>  $itemsOp
     */
    private function imprimirTotales(Collection $items, Collection $itemsOp, Insumo $insumo): void
    {
        $demandaPresupuestos = $items->sum(
            fn (PresupuestoItem $item): float => (float) ($item->demandaInsumos()[$insumo->id] ?? 0),
        );
        $demandaOrdenes = $itemsOp->sum(
            fn (OrdenProduccionItem $item): float => (float) (
                $item->insumos->firstWhere('insumo_id', $insumo->id)?->cantidadPendiente() ?? 0
            ),
        );
        $demandaTotal = $demandaPresupuestos + $demandaOrdenes;

        $reservasActivas = ReservaStock::query()
            ->activa()
            ->where('insumo_id', $insumo->id)
            ->get();

        $reservadoTotal = (float) $reservasActivas->sum('cantidad_reservada');
        $reservadoPresupuestos = (float) $reservasActivas
            ->whereNotNull('presupuesto_id')
            ->sum('cantidad_reservada');
        $reservadoOrdenes = (float) $reservasActivas
            ->whereNotNull('orden_produccion_id')
            ->sum('cantidad_reservada');

        $this->info('Totales:');
        $this->line('  Demanda de mobiliarios (presupuestos): '.$this->formatoCantidad($demandaPresupuestos));
        $this->line('  Demanda de mobiliarios (órdenes de producción): '.$this->formatoCantidad($demandaOrdenes));
        $this->line('  Demanda de mobiliarios (total): '.$this->formatoCantidad($demandaTotal));
        $this->line('  Reservas activas (presupuestos): '.$this->formatoCantidad($reservadoPresupuestos));
        $this->line('  Reservas activas (órdenes de producción): '.$this->formatoCantidad($reservadoOrdenes));
        $this->line('  Reservas activas (total): '.$this->formatoCantidad($reservadoTotal));
        $this->line('  Diferencia (reservado − demanda): '.$this->formatoCantidad($reservadoTotal - $demandaTotal));
    }

    private function cantidadInsumoEnBom(PresupuestoItem $item, int $insumoId): float
    {
        return (float) ($item->mobiliario?->composicionTecnica
            ?->where('insumo_id', $insumoId)
            ->where('es_componente_casco', false)
            ->sum('cantidad') ?? 0);
    }

    private function formatoCantidad(float $valor): string
    {
        return fmod($valor, 1.0) === 0.0
            ? (string) (int) $valor
            : number_format($valor, 4, '.', '');
    }
}
