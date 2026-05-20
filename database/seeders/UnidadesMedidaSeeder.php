<?php

namespace Database\Seeders;

use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class UnidadesMedidaSeeder extends Seeder
{
    public function run(): void
    {
        $unidades = [
            ['nombre' => 'Metro',          'abreviatura' => 'm'],
            ['nombre' => 'Centímetro',     'abreviatura' => 'cm'],
            ['nombre' => 'Milímetro',      'abreviatura' => 'mm'],
            ['nombre' => 'Metro cuadrado', 'abreviatura' => 'm²'],
            ['nombre' => 'Kilogramo',      'abreviatura' => 'kg'],
            ['nombre' => 'Gramo',          'abreviatura' => 'g'],
            ['nombre' => 'Litro',          'abreviatura' => 'L'],
            ['nombre' => 'Unidad',         'abreviatura' => 'u'],
            ['nombre' => 'Par',            'abreviatura' => 'par'],
            ['nombre' => 'Juego',          'abreviatura' => 'juego'],
            ['nombre' => 'Rollo',          'abreviatura' => 'rollo'],
            ['nombre' => 'Plancha',        'abreviatura' => 'plancha'],
        ];

        foreach ($unidades as $data) {
            UnidadMedida::firstOrCreate(
                ['abreviatura' => $data['abreviatura']],
                array_merge($data, ['activo' => true])
            );
        }

        $this->command->info('Unidades de medida creadas correctamente.');
    }
}
