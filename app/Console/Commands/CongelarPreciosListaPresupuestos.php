<?php

namespace App\Console\Commands;

use App\Models\Presupuesto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class CongelarPreciosListaPresupuestos extends Command
{
    protected $signature = 'presupuestos:congelar-precios-lista
                            {--dry-run : Solo mostrar cambios sin persistir}
                            {--codigo= : Código de un presupuesto puntual}';

    protected $description = 'Copia el precio de lista solo a ítems sin precio individual (mobiliarios y sillas)';

    private const ESTADOS = ['en_revision', 'aprobado', 'confirmado', 'pagado','entregado'];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $codigo = $this->option('codigo');

        if ($dryRun) {
            $this->warn('Modo dry-run: no se persistirán cambios.');
        }

        $query = Presupuesto::query()
            ->whereIn('estado', self::ESTADOS)
            ->with(['agencia.proyecto', 'items.mobiliario', 'items.insumo.marcasSilla']);

        if (is_string($codigo) && $codigo !== '') {
            $query->where('codigo', $codigo);
        }

        $presupuestos = $query->orderBy('codigo')->get();

        if ($presupuestos->isEmpty()) {
            if (is_string($codigo) && $codigo !== '') {
                $this->error("No se encontró un presupuesto {$codigo} en los estados: ".implode(', ', self::ESTADOS).'.');

                return self::FAILURE;
            }

            $this->info('No hay presupuestos para procesar.');

            return self::SUCCESS;
        }

        $actualizados = 0;
        $conPrecioIndividual = 0;
        $omitidos = 0;
        $filasCambio = [];
        $itemsAActualizar = [];

        foreach ($presupuestos as $presupuesto) {
            foreach ($presupuesto->items as $item) {
                $item->setRelation('presupuesto', $presupuesto);

                if ($item->precio_unitario !== null) {
                    $conPrecioIndividual++;

                    continue;
                }

                $precioLista = $item->precioListaCatalogo();

                if ($precioLista === null) {
                    $omitidos++;

                    continue;
                }

                $precioLista = round($precioLista, 2);
                $actualizados++;
                $itemsAActualizar[] = [$item, $precioLista];
                $filasCambio[] = [
                    $presupuesto->codigo,
                    $item->item_codigo,
                    $item->item_nombre,
                    number_format($precioLista, 2, '.', ''),
                ];
            }
        }

        if ($filasCambio !== []) {
            $this->newLine();
            $this->table(
                ['Presupuesto', 'Código', 'Ítem', 'Precio lista'],
                $filasCambio
            );
        }

        $this->newLine();
        $this->info('Resumen:');
        $this->line('  Presupuestos procesados: '.$presupuestos->count());
        $this->line("  Ítems actualizados: {$actualizados}");
        $this->line("  Ítems con precio individual (sin modificar): {$conPrecioIndividual}");
        $this->line("  Ítems omitidos (sin precio de lista): {$omitidos}");

        if ($dryRun) {
            $this->newLine();
            $this->comment('Ejecutá sin --dry-run para aplicar cambios.');

            return self::SUCCESS;
        }

        if ($actualizados === 0) {
            $this->info('No hay precios para actualizar.');

            return self::SUCCESS;
        }

        if ($this->input->isInteractive() && ! $this->confirm(
            "¿Congelar precios de lista en {$presupuestos->count()} presupuesto(s) ({$actualizados} ítem(s))?"
        )) {
            $this->info('Cancelado.');

            return self::SUCCESS;
        }

        DB::beginTransaction();

        try {
            foreach ($itemsAActualizar as [$item, $precioLista]) {
                $item->update(['precio_unitario' => $precioLista]);
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            $this->error('Error al congelar precios: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info('Precios de lista congelados correctamente.');

        return self::SUCCESS;
    }
}
