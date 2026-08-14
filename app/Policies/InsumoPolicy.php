<?php

namespace App\Policies;

use App\Models\Insumo;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;

class InsumoPolicy
{
    use AdministradorOnlyMutations;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function view(User $user, Insumo $insumo): bool
    {
        return $user->hasAnyRole(['Administrador', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }
}
