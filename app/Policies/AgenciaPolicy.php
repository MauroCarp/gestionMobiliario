<?php

namespace App\Policies;

use App\Models\Agencia;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;

class AgenciaPolicy
{
    use AdministradorOnlyMutations;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function view(User $user, Agencia $agencia): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }
}
