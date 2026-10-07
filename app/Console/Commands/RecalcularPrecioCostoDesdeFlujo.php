<?php

namespace App\Console\Commands;

use App\Models\PlantillaFlujoExterno;
use App\Services\PrecioCostoDesdeFlujoResult;
use App\Services\PrecioCostoDesdeFlujoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class RecalcularPrecioCostoDesdeFlujo extends Command
{
    protected $signature = 'insumos:recalcular-precio-desde-flujo
                            {--dry-run : Solo mostrar cambios sin persistir}';

    protected $description = 'Actualiza precio_costo de insumos con una sola plantilla de flujo externo (suma de ultimo_precio de etapas)';

    public function handle(PrecioCostoDesdeFlujoService $service): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('Modo dry-run: no se persistirán cambios.');
        }

        $plantillas = PlantillaFlujoExterno::query()
            ->where('entidad_tipo', 'insumo')
            ->orderBy('id')
            ->get()
            ->groupBy('entidad_id');

        if ($plantillas->isEmpty()) {
            $this->info('No hay plantillas de flujo externo asignadas a insumos.');

            return self::SUCCESS;
        }

        $actualizados = 0;
        $sinCambio = 0;
        $sinPrecios = 0;
        $revisionManual = 0;
        $filasCambio = [];
        $filasManual = [];
        $resultadosAAplicar = [];

        foreach ($plantillas->keys() as $entidadId) {
            $resultado = $service->evaluar((int) $entidadId);

            if ($resultado->estado === PrecioCostoDesdeFlujoResult::INSUMO_NO_ENCONTRADO) {
                $this->warn("Insumo #{$entidadId} no encontrado; se omite.");

                continue;
            }

            if ($resultado->estado === PrecioCostoDesdeFlujoResult::REVISION_MANUAL) {
                $revisionManual++;
                $filasManual[] = [
                    $resultado->insumo->codigo,
                    $resultado->insumo->nombre,
                    implode(', ', $resultado->plantillaNombres),
                ];

                continue;
            }

            if (in_array($resultado->estado, [
                PrecioCostoDesdeFlujoResult::SIN_PRECIOS,
                PrecioCostoDesdeFlujoResult::SIN_PLANTILLAS,
            ], true)) {
                $sinPrecios++;

                continue;
            }

            if ($resultado->estado === PrecioCostoDesdeFlujoResult::SIN_CAMBIO) {
                $sinCambio++;

                continue;
            }

            $actualizados++;
            $resultadosAAplicar[] = $resultado;
            $filasCambio[] = [
                $resultado->insumo->codigo,
                $resultado->insumo->nombre,
                $resultado->plantillaNombre,
                $resultado->precioActual === null ? '—' : number_format($resultado->precioActual, 2, '.', ''),
                number_format($resultado->nuevoPrecio, 2, '.', ''),
            ];
        }

        if ($filasManual !== []) {
            $this->newLine();
            $this->warn('Insumos con más de una plantilla (resolver a mano):');
            $this->table(['Código', 'Insumo', 'Plantillas'], $filasManual);
        }

        if ($filasCambio !== []) {
            $this->newLine();
            $this->info('Cambios de precio_costo:');
            $this->table(['Código', 'Insumo', 'Plantilla', 'Precio actual', 'Nuevo precio'], $filasCambio);
        }

        $this->newLine();
        $this->info('Resumen:');
        $this->line('  Insumos con plantilla: '.$plantillas->count());
        $this->line("  Actualizados: {$actualizados}");
        $this->line("  Sin cambio: {$sinCambio}");
        $this->line("  Sin precios en etapas: {$sinPrecios}");
        $this->line("  Pendientes de revisión manual: {$revisionManual}");

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
            "¿Actualizar precio_costo en {$actualizados} insumo(s)?"
        )) {
            $this->info('Cancelado.');

            return self::SUCCESS;
        }

        DB::beginTransaction();

        try {
            foreach ($resultadosAAplicar as $resultado) {
                $service->aplicar($resultado);
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            $this->error('Error al actualizar precios: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info('Precios de costo actualizados correctamente.');

        return self::SUCCESS;
    }
}
