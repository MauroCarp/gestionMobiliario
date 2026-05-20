<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Insumo;

class InsumoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function view(User $user, Insumo $insumo): bool
    {
        return $user->hasAnyRole(['Administrador', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Depósito / Stock']);
    }

    public function update(User $user, Insumo $insumo): bool
    {
        return $user->hasAnyRole(['Administrador', 'Depósito / Stock']);
    }

    public function delete(User $user, Insumo $insumo): bool
    {
        return $user->hasRole('Administrador');
    }

    public function restore(User $user, Insumo $insumo): bool
    {
        return $user->hasRole('Administrador');
    }

    public function forceDelete(User $user, Insumo $insumo): bool
    {
        return $user->hasRole('Administrador');
    }
}
