<?php

namespace Database\Seeders;

use App\Models\Provincia;
use Illuminate\Database\Seeder;

class ProvinciaSeeder extends Seeder
{
    public function run(): void
    {
        $provincias = [
            ['nombre' => 'Buenos Aires', 'codigo' => '06'],
            ['nombre' => 'Catamarca', 'codigo' => '10'],
            ['nombre' => 'Chaco', 'codigo' => '16'],
            ['nombre' => 'Chubut', 'codigo' => '20'],
            ['nombre' => 'Ciudad Autónoma de Buenos Aires', 'codigo' => '02'],
            ['nombre' => 'Córdoba', 'codigo' => '14'],
            ['nombre' => 'Corrientes', 'codigo' => '18'],
            ['nombre' => 'Entre Ríos', 'codigo' => '30'],
            ['nombre' => 'Formosa', 'codigo' => '22'],
            ['nombre' => 'Jujuy', 'codigo' => '38'],
            ['nombre' => 'La Pampa', 'codigo' => '42'],
            ['nombre' => 'La Rioja', 'codigo' => '46'],
            ['nombre' => 'Mendoza', 'codigo' => '50'],
            ['nombre' => 'Misiones', 'codigo' => '54'],
            ['nombre' => 'Neuquén', 'codigo' => '58'],
            ['nombre' => 'Río Negro', 'codigo' => '62'],
            ['nombre' => 'Salta', 'codigo' => '66'],
            ['nombre' => 'San Juan', 'codigo' => '70'],
            ['nombre' => 'San Luis', 'codigo' => '74'],
            ['nombre' => 'Santa Cruz', 'codigo' => '78'],
            ['nombre' => 'Santa Fe', 'codigo' => '82'],
            ['nombre' => 'Santiago del Estero', 'codigo' => '86'],
            ['nombre' => 'Tierra del Fuego', 'codigo' => '94'],
            ['nombre' => 'Tucumán', 'codigo' => '90'],
        ];

        foreach ($provincias as $provincia) {
            Provincia::firstOrCreate(
                ['nombre' => $provincia['nombre']],
                ['codigo' => $provincia['codigo']]
            );
        }
    }
}
