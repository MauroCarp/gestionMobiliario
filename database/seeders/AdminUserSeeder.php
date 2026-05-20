<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gestionmobiliario.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('Admin1234!'),
                'activo'   => true,
            ]
        );
        $admin->assignRole('Administrador');

        $ventas = User::firstOrCreate(
            ['email' => 'ventas@gestionmobiliario.com'],
            [
                'name'     => 'Usuario Ventas',
                'password' => Hash::make('Ventas1234!'),
                'activo'   => true,
            ]
        );
        $ventas->assignRole('Ventas');

        $produccion = User::firstOrCreate(
            ['email' => 'produccion@gestionmobiliario.com'],
            [
                'name'     => 'Usuario Producción',
                'password' => Hash::make('Prod1234!'),
                'activo'   => true,
            ]
        );
        $produccion->assignRole('Producción');

        $this->command->info('Usuarios iniciales creados correctamente.');
    }
}
