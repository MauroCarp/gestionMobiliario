<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Marca;

class MarcaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function view(User $user, Marca $marca): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas']);
    }

    public function update(User $user, Marca $marca): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas']);
    }

    public function delete(User $user, Marca $marca): bool
    {
        return $user->hasRole('Administrador');
    }

    public function restore(User $user, Marca $marca): bool
    {
        return $user->hasRole('Administrador');
    }

    public function forceDelete(User $user, Marca $marca): bool
    {
        return $user->hasRole('Administrador');
    }
}
