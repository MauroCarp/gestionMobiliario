<?php

namespace App\Policies;

use App\Models\Mobiliario;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;

class MobiliarioPolicy
{
    use AdministradorOnlyMutations;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function view(User $user, Mobiliario $mobiliario): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }
}
