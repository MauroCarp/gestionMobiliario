<?php

namespace App\Console\Commands;

use App\Models\Insumo;
use App\Models\PlantillaEtapa;
use App\Models\PlantillaFlujoExterno;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class RecalcularPrecioCostoDesdeFlujo extends Command
{
    protected $signature = 'insumos:recalcular-precio-desde-flujo
                            {--dry-run : Solo mostrar cambios sin persistir}';

    protected $description = 'Actualiza precio_costo de insumos con una sola plantilla de flujo externo (suma de ultimo_precio de etapas)';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('Modo dry-run: no se persistirán cambios.');
        }

        $plantillas = PlantillaFlujoExterno::query()
            ->where('entidad_tipo', 'insumo')
            ->with('etapas')
            ->orderBy('id')
            ->get()
            ->groupBy('entidad_id');

        if ($plantillas->isEmpty()) {
            $this->info('No hay plantillas de flujo externo asignadas a insumos.');

            return self::SUCCESS;
        }

        $insumos = Insumo::query()
            ->whereIn('id', $plantillas->keys())
            ->get()
            ->keyBy('id');

        $actualizados = 0;
        $sinCambio = 0;
        $sinPrecios = 0;
        $revisionManual = 0;
        $filasCambio = [];
        $filasManual = [];
        $insumosAActualizar = [];

        foreach ($plantillas as $entidadId => $grupo) {
            $insumo = $insumos->get((int) $entidadId);

            if (! $insumo) {
                $this->warn("Insumo #{$entidadId} no encontrado; se omite.");

                continue;
            }

            if ($grupo->count() > 1) {
                $revisionManual++;
                $filasManual[] = [
                    $insumo->codigo,
                    $insumo->nombre,
                    $grupo->pluck('nombre')->implode(', '),
                ];

                continue;
            }

            /** @var PlantillaFlujoExterno $plantilla */
            $plantilla = $grupo->first();
            $etapas = $plantilla->etapas;
            $tienePrecio = $etapas->contains(
                fn (PlantillaEtapa $etapa): bool => $etapa->ultimo_precio !== null
            );

            if (! $tienePrecio) {
                $sinPrecios++;

                continue;
            }

            $nuevoCosto = round(
                (float) $etapas->sum(fn (PlantillaEtapa $etapa): float => (float) ($etapa->ultimo_precio ?? 0)),
                2
            );
            $actual = $insumo->precio_costo === null
                ? null
                : round((float) $insumo->precio_costo, 2);

            if ($actual === $nuevoCosto) {
                $sinCambio++;

                continue;
            }

            $actualizados++;
            $insumosAActualizar[] = [$insumo, $nuevoCosto];
            $filasCambio[] = [
                $insumo->codigo,
                $insumo->nombre,
                $plantilla->nombre,
                $actual === null ? '—' : number_format($actual, 2, '.', ''),
                number_format($nuevoCosto, 2, '.', ''),
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
            foreach ($insumosAActualizar as [$insumo, $nuevoCosto]) {
                $insumo->update(['precio_costo' => $nuevoCosto]);
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
