<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mobiliario;

class MobiliarioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function view(User $user, Mobiliario $mobiliario): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Producción']);
    }

    public function update(User $user, Mobiliario $mobiliario): bool
    {
        return $user->hasAnyRole(['Administrador', 'Producción']);
    }

    public function delete(User $user, Mobiliario $mobiliario): bool
    {
        return $user->hasRole('Administrador');
    }

    public function restore(User $user, Mobiliario $mobiliario): bool
    {
        return $user->hasRole('Administrador');
    }

    public function forceDelete(User $user, Mobiliario $mobiliario): bool
    {
        return $user->hasRole('Administrador');
    }
}
