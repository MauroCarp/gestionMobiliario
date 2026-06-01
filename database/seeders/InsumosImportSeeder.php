<?php

namespace Database\Seeders;

use App\Models\Insumo;
use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class InsumosImportSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('insumos.csv');

        if (! file_exists($csvPath)) {
            $this->command->error("Archivo no encontrado: {$csvPath}");
            return;
        }

        // ID de "Unidad" como fallback para filas sin unidad de medida
        $unidadDefault = UnidadMedida::where('abreviatura', 'u')->value('id');

        if (! $unidadDefault) {
            $this->command->error('No se encontró la unidad de medida "Unidad" (abreviatura: u) en la base de datos.');
            return;
        }

        $handle = fopen($csvPath, 'r');

        // Omitir encabezados
        fgetcsv($handle, 0, ';');

        $imported = 0;
        $skipped  = 0;

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $nombre         = trim($row[0] ?? '');
            $unidadMedidaId = isset($row[1]) && trim($row[1]) !== ''
                ? (int) trim($row[1])
                : $unidadDefault;

            if (empty($nombre)) {
                $skipped++;
                continue;
            }

            Insumo::firstOrCreate(
                ['nombre' => $nombre],
                [
                    'unidad_medida_id' => $unidadMedidaId,
                    'activo'           => true,
                ]
            );

            $imported++;
        }

        fclose($handle);

        $this->command->info("Importación completada: {$imported} insumos importados, {$skipped} filas omitidas.");
    }
}
