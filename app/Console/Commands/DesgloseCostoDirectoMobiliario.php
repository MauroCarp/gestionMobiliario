<?php

namespace App\Console\Commands;

use App\Models\Mobiliario;
use Illuminate\Console\Command;

class DesgloseCostoDirectoMobiliario extends Command
{
    protected $signature = 'mobiliarios:costo-directo {codigo : Código interno del mobiliario}';

    protected $description = 'Muestra el desglose de costo directo (insumos de la composición vigente y etapas de plantillas activas)';

    public function handle(): int
    {
        $codigo = trim((string) $this->argument('codigo'));

        $mobiliario = Mobiliario::query()
            ->with([
                'composicionTecnica.insumo.unidadMedida',
                'plantillaFlujos.etapas.tipoProceso',
                'plantillaFlujos.etapas.tercero',
            ])
            ->where('codigo_interno', $codigo)
            ->first();

        if (! $mobiliario) {
            $this->error("No se encontró el mobiliario con código {$codigo}.");

            return self::FAILURE;
        }

        $desglose = $mobiliario->desgloseCostoDirecto();
        $precioLista = $mobiliario->precio === null
            ? '—'
            : $this->formato($mobiliario->precio);

        $this->info("{$mobiliario->codigo_interno} — {$mobiliario->nombre}");
        $this->line('Versión: '.($mobiliario->version_actual ?? '—'));
        $this->line("Precio de lista: {$precioLista}");

        $this->newLine();
        $this->info('Insumos (composición vigente)');

        if ($desglose['insumos'] === []) {
            $this->line('  Sin insumos en la composición vigente.');
        } else {
            $this->table(
                ['Código', 'Nombre', 'Cantidad', 'Unidad', 'Precio costo', 'Subtotal', 'Casco'],
                array_map(fn (array $linea): array => [
                    $linea['codigo'] ?? '—',
                    $linea['nombre'] ?? '—',
                    $this->formato($linea['cantidad']),
                    $linea['unidad'] ?? '—',
                    $this->formato($linea['precio_costo']),
                    $this->formato($linea['subtotal']),
                    $linea['es_casco'] ? 'sí' : '',
                ], $desglose['insumos']),
            );
        }

        $this->line('Subtotal insumos: '.$this->formato($desglose['subtotal_insumos']));

        $this->newLine();
        $this->info('Etapas (plantillas activas)');

        if ($desglose['etapas'] === []) {
            $this->line('  Sin etapas de plantilla activa.');
        } else {
            $this->table(
                ['Plantilla', 'Orden', 'Proceso', 'Tercero', 'Último precio'],
                array_map(fn (array $etapa): array => [
                    $etapa['plantilla'] ?? '—',
                    $etapa['orden'] ?? '—',
                    $etapa['tipo_proceso'] ?? '—',
                    $etapa['tercero'] ?? '—',
                    $this->formato($etapa['ultimo_precio']),
                ], $desglose['etapas']),
            );
        }

        $this->line('Subtotal etapas: '.$this->formato($desglose['subtotal_etapas']));

        $this->newLine();
        $this->info('Costo Directo: '.$this->formato($desglose['total']));

        if ($desglose['plantillas_inactivas'] !== []) {
            $this->newLine();
            $this->warn(
                'Plantillas inactivas (no entran al total): '.implode(', ', $desglose['plantillas_inactivas'])
            );
        }

        return self::SUCCESS;
    }

    private function formato(float $valor): string
    {
        return number_format($valor, 2, '.', '');
    }
}
