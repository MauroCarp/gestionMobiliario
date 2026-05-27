<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Proyecto;

class ProyectoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function view(User $user, Proyecto $proyecto): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Administrador');
    }

    public function update(User $user, Proyecto $proyecto): bool
    {
        return $user->hasRole('Administrador');
    }

    public function delete(User $user, Proyecto $proyecto): bool
    {
        return $user->hasRole('Administrador');
    }

    public function restore(User $user, Proyecto $proyecto): bool
    {
        return $user->hasRole('Administrador');
    }

    public function forceDelete(User $user, Proyecto $proyecto): bool
    {
        return $user->hasRole('Administrador');
    }
}
