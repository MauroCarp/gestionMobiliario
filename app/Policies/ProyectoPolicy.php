<?php

namespace App\Policies;

use App\Models\Proyecto;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;

class ProyectoPolicy
{
    use AdministradorOnlyMutations;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }

    public function view(User $user, Proyecto $proyecto): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Depósito / Stock', 'Solo lectura']);
    }
}
