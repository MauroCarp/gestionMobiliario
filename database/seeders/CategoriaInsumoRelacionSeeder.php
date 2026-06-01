<?php

namespace Database\Seeders;

use App\Models\Insumo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaInsumoRelacionSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('insumos.csv');

        if (! file_exists($csvPath)) {
            $this->command->error("Archivo no encontrado: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');

        // Omitir encabezados
        fgetcsv($handle, 0, ';');

        // Pre-cargar insumos en memoria: nombre => id
        $insumosMap = Insumo::pluck('id', 'nombre');

        $vinculados   = 0;
        $sinCategoria = 0;
        $sinInsumo    = 0;

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $nombre          = trim($row[0] ?? '');
            $categoriaInsumoId = isset($row[2]) && trim($row[2]) !== ''
                ? (int) trim($row[2])
                : null;

            if (empty($nombre) || $categoriaInsumoId === null) {
                $sinCategoria++;
                continue;
            }

            $insumoId = $insumosMap[$nombre] ?? null;

            if (! $insumoId) {
                $this->command->warn("Insumo no encontrado: '{$nombre}'");
                $sinInsumo++;
                continue;
            }

            DB::table('categoria_insumo_insumo')->updateOrInsert(
                [
                    'insumo_id'          => $insumoId,
                    'categoria_insumo_id' => $categoriaInsumoId,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $vinculados++;
        }

        fclose($handle);

        $this->command->info("Relaciones creadas: {$vinculados}");
        $this->command->info("Filas sin categoría (omitidas): {$sinCategoria}");

        if ($sinInsumo > 0) {
            $this->command->warn("Insumos no encontrados en BD: {$sinInsumo}");
        }
    }
}
