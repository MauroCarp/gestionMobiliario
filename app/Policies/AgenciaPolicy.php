<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Agencia;

class AgenciaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function view(User $user, Agencia $agencia): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Administrador');
    }

    public function update(User $user, Agencia $agencia): bool
    {
        return $user->hasRole('Administrador');
    }

    public function delete(User $user, Agencia $agencia): bool
    {
        return $user->hasRole('Administrador');
    }

    public function restore(User $user, Agencia $agencia): bool
    {
        return $user->hasRole('Administrador');
    }

    public function forceDelete(User $user, Agencia $agencia): bool
    {
        return $user->hasRole('Administrador');
    }
}
