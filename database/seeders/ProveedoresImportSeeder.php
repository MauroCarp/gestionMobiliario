<?php

namespace Database\Seeders;

use App\Models\Ciudad;
use App\Models\Provincia;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedoresImportSeeder extends Seeder
{
    /**
     * Mapeo de condiciones IVA del CSV a los valores del modelo.
     */
    private array $condicionIvaMap = [
        'RESPONSABLE INSCRIPTO' => 'responsable_inscripto',
        'RESP.MONOTRIBUTO'      => 'monotributista',
        'MONOTRIBUTISTA'        => 'monotributista',
        'EXENTO'                => 'exento',
        'NO RESPONSABLE'        => 'no_responsable',
        'CONSUMIDOR FINAL'      => 'consumidor_final',
    ];

    public function run(): void
    {
        $csvPath = base_path('proveedores.csv');

        if (! file_exists($csvPath)) {
            $this->command->error("Archivo no encontrado: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');

        // Omitir la fila de encabezados
        fgetcsv($handle, 0, ';');

        $imported = 0;
        $skipped  = 0;

        DB::transaction(function () use ($handle, &$imported, &$skipped) {
            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                // Ignorar filas vacías
                if (empty(array_filter($row))) {
                    continue;
                }

                $codigo          = $this->clean($row[0]  ?? '');
                $razonSocial     = $this->clean($row[1]  ?? '');
                $nombreComercial = $this->clean($row[2]  ?? '');
                $cuit            = $this->clean($row[3]  ?? '');
                $condicionIvaRaw = strtoupper(trim($row[4] ?? ''));
                $direccion       = $this->clean($row[5]  ?? '');
                $localidad       = $this->clean($row[6]  ?? '');
                $provinciaName   = $this->clean($row[7]  ?? '');
                $telefono        = $this->clean($row[8]  ?? '');
                $email           = $this->clean($row[9]  ?? '');
                $contacto        = $this->clean($row[10] ?? '');
                $estadoRaw       = trim($row[11] ?? '1');
                $observaciones   = $this->clean($row[12] ?? '');
                $condicionPago   = $this->clean($row[13] ?? '');
                $listaPrecio     = $this->clean($row[14] ?? '');

                if (empty($codigo) || empty($razonSocial)) {
                    $skipped++;
                    continue;
                }

                // Normalizar código (quitar puntos de miles: "1.021" → "1021")
                $codigoNorm = str_replace('.', '', $codigo);

                $condicionIva = $this->condicionIvaMap[$condicionIvaRaw] ?? null;
                $activo       = $estadoRaw === '1';

                // Resolver provincia
                $provinciaId = null;
                if ($provinciaName) {
                    $provincia   = Provincia::firstOrCreate(['nombre' => $provinciaName]);
                    $provinciaId = $provincia->id;
                }

                // Resolver ciudad
                $ciudadId = null;
                if ($localidad && $provinciaId) {
                    $ciudad   = Ciudad::firstOrCreate(
                        ['nombre' => $localidad, 'provincia_id' => $provinciaId]
                    );
                    $ciudadId = $ciudad->id;
                }

                Proveedor::updateOrCreate(
                    ['codigo' => $codigoNorm],
                    [
                        'razon_social'     => $razonSocial,
                        'nombre_comercial' => $nombreComercial ?: null,
                        'cuit'             => $cuit            ?: null,
                        'condicion_iva'    => $condicionIva,
                        'direccion'        => $direccion       ?: null,
                        'provincia_id'     => $provinciaId,
                        'ciudad_id'        => $ciudadId,
                        'telefono'         => $telefono        ?: null,
                        'email'            => $email           ?: null,
                        'persona_contacto' => $contacto        ?: null,
                        'activo'           => $activo,
                        'observaciones'    => $observaciones   ?: null,
                        'condicion_pago'   => $condicionPago   ?: null,
                        'lista_precio'     => $listaPrecio     ?: null,
                    ]
                );

                $imported++;
            }
        });

        fclose($handle);

        $this->command->info("Importación completada: {$imported} proveedores importados, {$skipped} filas omitidas.");
    }

    /**
     * Limpia un valor del CSV: trim y convierte "#N/D" / vacíos a null-safe string.
     */
    private function clean(string $value): string
    {
        $value = trim($value);

        if ($value === '#N/D' || $value === 'N/D') {
            return '';
        }

        return $value;
    }
}
