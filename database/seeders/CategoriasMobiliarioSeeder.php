<?php

namespace Database\Seeders;

use App\Models\CategoriaMobiliario;
use Illuminate\Database\Seeder;

class CategoriasMobiliarioSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Escritorios',   'icono' => 'heroicon-o-computer-desktop', 'orden' => 1],
            ['nombre' => 'Recepciones',   'icono' => 'heroicon-o-building-office',  'orden' => 2],
            ['nombre' => 'Góndolas',      'icono' => 'heroicon-o-archive-box',       'orden' => 3],
            ['nombre' => 'Exhibidores',   'icono' => 'heroicon-o-presentation-chart-bar', 'orden' => 4],
            ['nombre' => 'Backoffice',    'icono' => 'heroicon-o-briefcase',         'orden' => 5],
            ['nombre' => 'Cartelería',    'icono' => 'heroicon-o-megaphone',         'orden' => 6],
            ['nombre' => 'Otras',         'icono' => 'heroicon-o-ellipsis-horizontal', 'orden' => 7],
        ];

        foreach ($categorias as $data) {
            CategoriaMobiliario::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['nombre'])],
                array_merge($data, ['activo' => true])
            );
        }

        $this->command->info('Categorías de mobiliario creadas correctamente.');
    }
}
