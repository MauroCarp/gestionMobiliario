<?php

namespace Database\Seeders;

use App\Models\Provincia;
use App\Models\Ciudad;
use Illuminate\Database\Seeder;

class CiudadSeeder extends Seeder
{
    public function run(): void
    {
        $ciudades = [
            'Buenos Aires' => ['La Plata', 'Mar del Plata', 'Bragado', 'Junín', 'Tandil', 'Bahía Blanca'],
            'Catamarca' => ['San Fernando del Valle de Catamarca', 'Belén'],
            'Chaco' => ['Resistencia', 'Corrientes'],
            'Chubut' => ['Rawson', 'Comodoro Rivadavia', 'Trelew'],
            'Ciudad Autónoma de Buenos Aires' => ['San Nicolás', 'Recoleta', 'Palermo', 'Belgrano', 'Flores', 'San Telmo', 'Caballito', 'La Boca'],
            'Córdoba' => ['Córdoba', 'Río Cuarto', 'Villa María', 'San Francisco'],
            'Corrientes' => ['Corrientes', 'Oberá', 'Paso de la Cruz'],
            'Entre Ríos' => ['Paraná', 'Concordia', 'Gualeguaychú'],
            'Formosa' => ['Formosa', 'Clorinda'],
            'Jujuy' => ['San Salvador de Jujuy', 'Palpalá', 'Perico'],
            'La Pampa' => ['Santa Rosa', 'General Pico'],
            'La Rioja' => ['La Rioja', 'Chamical'],
            'Mendoza' => ['Mendoza', 'San Rafael', 'Godoy Cruz', 'Las Heras'],
            'Misiones' => ['Posadas', 'Oberá', 'Puerto Iguazú'],
            'Neuquén' => ['Neuquén', 'San Martín de los Andes', 'Junín de los Andes'],
            'Río Negro' => ['Viedma', 'San Carlos de Bariloche', 'General Roca'],
            'Salta' => ['Salta', 'San Salvador de Jujuy', 'Tartagal'],
            'San Juan' => ['San Juan', 'Chimbas', 'Caucete'],
            'San Luis' => ['San Luis', 'Villa Mercedes'],
            'Santa Cruz' => ['Río Gallegos', 'Calafate', 'El Chaltén'],
            'Santa Fe' => ['Santa Fe', 'Rosario', 'Venado Tuerto', 'Rafaela'],
            'Santiago del Estero' => ['Santiago del Estero', 'La Banda'],
            'Tierra del Fuego' => ['Ushuaia', 'Río Grande'],
            'Tucumán' => ['San Miguel de Tucumán', 'Tafí Viejo', 'Yerba Buena'],
        ];

        foreach ($ciudades as $nombreProvincia => $nombresCiudades) {
            $provincia = Provincia::where('nombre', $nombreProvincia)->first();
            
            if ($provincia) {
                foreach ($nombresCiudades as $nombreCiudad) {
                    Ciudad::firstOrCreate(
                        ['nombre' => $nombreCiudad, 'provincia_id' => $provincia->id],
                        ['codigo_postal' => null]
                    );
                }
            }
        }
    }
}
