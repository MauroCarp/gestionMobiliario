<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions by module
        $permissions = [
            // Marcas
            'marcas.viewAny', 'marcas.view', 'marcas.create', 'marcas.update', 'marcas.delete',
            // Agencias
            'agencias.viewAny', 'agencias.view', 'agencias.create', 'agencias.update', 'agencias.delete',
            // Proyectos
            'proyectos.viewAny', 'proyectos.view', 'proyectos.create', 'proyectos.update', 'proyectos.delete',
            // Mobiliarios
            'mobiliarios.viewAny', 'mobiliarios.view', 'mobiliarios.create', 'mobiliarios.update', 'mobiliarios.delete',
            // Categorías
            'categorias.viewAny', 'categorias.view', 'categorias.create', 'categorias.update', 'categorias.delete',
            // Insumos
            'insumos.viewAny', 'insumos.view', 'insumos.create', 'insumos.update', 'insumos.delete',
            // Usuarios (solo admin)
            'usuarios.viewAny', 'usuarios.view', 'usuarios.create', 'usuarios.update', 'usuarios.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Administrador - full access
        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $admin->syncPermissions(Permission::all());

        // Ventas - solo lectura y gestión de consulta
        $ventas = Role::firstOrCreate(['name' => 'Ventas']);
        $ventas->syncPermissions([
            'marcas.viewAny', 'marcas.view',
            'agencias.viewAny', 'agencias.view',
            'proyectos.viewAny', 'proyectos.view',
            'mobiliarios.viewAny', 'mobiliarios.view',
            'categorias.viewAny', 'categorias.view',
        ]);

        // Producción - ver proyectos y mobiliarios
        $produccion = Role::firstOrCreate(['name' => 'Producción']);
        $produccion->syncPermissions([
            'proyectos.viewAny', 'proyectos.view',
            'mobiliarios.viewAny', 'mobiliarios.view',
            'categorias.viewAny', 'categorias.view',
            'insumos.viewAny', 'insumos.view',
        ]);

        // Depósito / Stock - ver insumos y mobiliarios
        $deposito = Role::firstOrCreate(['name' => 'Depósito / Stock']);
        $deposito->syncPermissions([
            'insumos.viewAny', 'insumos.view',
            'mobiliarios.viewAny', 'mobiliarios.view',
            'proyectos.viewAny', 'proyectos.view',
        ]);

        // Solo lectura - solo ver
        $soloLectura = Role::firstOrCreate(['name' => 'Solo lectura']);
        $soloLectura->syncPermissions(
            Permission::where('name', 'like', '%.viewAny')
                ->orWhere('name', 'like', '%.view')
                ->get()
        );

        $this->command->info('Roles y permisos creados correctamente.');
    }
}
