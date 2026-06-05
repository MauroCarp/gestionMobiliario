<?php

namespace Database\Seeders;

use App\Models\CategoriaInsumo;
use App\Models\Insumo;
use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class InsumosImportSeeder extends Seeder
{
    /**
     * Normaliza el nombre del CSV a la unidad de medida correspondiente.
     * Crea la unidad si no existe.
     */
    private function resolveUnidadMedida(string $csvValue): UnidadMedida
    {
        $map = [
            'UNIDAD'       => ['nombre' => 'Unidad',       'abreviatura' => 'u'],
            'METRO LINEAL' => ['nombre' => 'Metro lineal', 'abreviatura' => 'ml'],
            'LITROS'       => ['nombre' => 'Litro',        'abreviatura' => 'L'],
        ];

        $key  = strtoupper(trim($csvValue));
        $data = $map[$key] ?? ['nombre' => ucfirst(strtolower(trim($csvValue))), 'abreviatura' => strtolower(trim($csvValue))];

        return UnidadMedida::firstOrCreate(
            ['abreviatura' => $data['abreviatura']],
            array_merge($data, ['activo' => true])
        );
    }

    public function run(): void
    {
        $csvPath = base_path('insumos.csv');

        if (! file_exists($csvPath)) {
            $this->command->error("Archivo no encontrado: {$csvPath}");
            return;
        }

        // Fallback: Unidad
        $unidadDefault = $this->resolveUnidadMedida('UNIDAD');

        $handle = fopen($csvPath, 'r');

        // Omitir encabezado
        fgetcsv($handle, 0, ';');

        $imported = 0;
        $updated  = 0;
        $skipped  = 0;

        // Cache de categorías y unidades para evitar queries repetidas
        $categoriaCache    = [];
        $unidadMedidaCache = [];

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $nombre        = trim($row[0] ?? '');
            $categoriaNom  = trim($row[1] ?? '');
            $unidadNom     = trim($row[2] ?? '');
            $stockRaw      = trim($row[3] ?? '0');

            if (empty($nombre)) {
                $skipped++;
                continue;
            }

            // ── Unidad de medida ──────────────────────────────────────────
            if ($unidadNom !== '') {
                if (! isset($unidadMedidaCache[$unidadNom])) {
                    $unidadMedidaCache[$unidadNom] = $this->resolveUnidadMedida($unidadNom);
                }
                $unidadMedida = $unidadMedidaCache[$unidadNom];
            } else {
                $unidadMedida = $unidadDefault;
            }

            // ── Stock (valores no numéricos → 0) ─────────────────────────
            $stock = is_numeric($stockRaw) ? (float) $stockRaw : 0;

            // ── Insumo ────────────────────────────────────────────────────
            $exists = Insumo::where('nombre', $nombre)->first();

            if ($exists) {
                $exists->update([
                    'unidad_medida_id' => $unidadMedida->id,
                    'stock_actual'     => $stock,
                    'activo'           => true,
                ]);
                $insumo = $exists;
                $updated++;
            } else {
                $insumo = Insumo::create([
                    'nombre'           => $nombre,
                    'unidad_medida_id' => $unidadMedida->id,
                    'stock_actual'     => $stock,
                    'activo'           => true,
                ]);
                $imported++;
            }

            // ── Categoría (pivot) ─────────────────────────────────────────
            if ($categoriaNom !== '') {
                if (! isset($categoriaCache[$categoriaNom])) {
                    $categoriaCache[$categoriaNom] = CategoriaInsumo::firstOrCreate(
                        ['nombre' => $categoriaNom],
                        ['activo' => true]
                    );
                }
                $categoria = $categoriaCache[$categoriaNom];

                // syncWithoutDetaching: agrega sin remover categorías ya existentes
                $insumo->categoriasInsumo()->syncWithoutDetaching([$categoria->id]);
            }
        }

        fclose($handle);

        $this->command->info("Importación completada: {$imported} creados, {$updated} actualizados, {$skipped} filas omitidas.");
    }
}
