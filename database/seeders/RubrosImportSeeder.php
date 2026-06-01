<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use App\Models\Rubro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RubrosImportSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('rubros.csv');

        if (! file_exists($csvPath)) {
            $this->command->error("Archivo no encontrado: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');

        // Omitir encabezados
        fgetcsv($handle, 0, ';');

        $rows = [];

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            $codigo = trim($row[0] ?? '');
            $rubro1 = trim($row[1] ?? '');
            $rubro2 = trim($row[2] ?? '');

            if (empty($codigo)) {
                continue;
            }

            $rows[] = [
                'codigo' => str_replace('.', '', $codigo),
                'rubro1' => $rubro1 ?: null,
                'rubro2' => $rubro2 ?: null,
            ];
        }

        fclose($handle);

        // 1. DISTINCT de rubros (columnas 2 y 3 combinadas)
        $nombresRubros = collect($rows)
            ->flatMap(fn ($r) => array_filter([$r['rubro1'], $r['rubro2']]))
            ->unique()
            ->filter()
            ->values();

        $this->command->info("Rubros únicos encontrados: {$nombresRubros->count()}");

        // 2. Insertar rubros
        DB::transaction(function () use ($nombresRubros, $rows) {
            foreach ($nombresRubros as $nombre) {
                Rubro::firstOrCreate(['nombre' => $nombre]);
            }

            // Pre-cargar rubros en memoria para evitar N+1
            $rubrosMap = Rubro::pluck('id', 'nombre');

            // 3. Generar registros en proveedor_rubro
            $vinculados = 0;
            $sinProveedor = 0;

            foreach ($rows as $row) {
                $proveedor = Proveedor::where('codigo', $row['codigo'])->first();

                if (! $proveedor) {
                    $this->command->warn("Proveedor no encontrado para código: {$row['codigo']}");
                    $sinProveedor++;
                    continue;
                }

                $rubroIds = collect([$row['rubro1'], $row['rubro2']])
                    ->filter()
                    ->map(fn ($nombre) => $rubrosMap[$nombre] ?? null)
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();

                if (empty($rubroIds)) {
                    continue;
                }

                // sync(false) agrega sin eliminar los existentes y evita duplicados
                $proveedor->rubros()->syncWithoutDetaching($rubroIds);
                $vinculados++;
            }

            $this->command->info("Proveedores vinculados: {$vinculados}");
            if ($sinProveedor > 0) {
                $this->command->warn("Proveedores no encontrados: {$sinProveedor}");
            }
        });
    }
}
