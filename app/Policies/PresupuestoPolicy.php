<?php

namespace App\Policies;

use App\Models\Presupuesto;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;

class PresupuestoPolicy
{
    use AdministradorOnlyMutations;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Solo lectura']);
    }

    public function view(User $user, Presupuesto $presupuesto): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Solo lectura']);
    }

    public function update(User $user, Presupuesto $presupuesto): bool
    {
        if (! $presupuesto->puedeEditar()) {
            return false;
        }

        return $user->hasRole('Administrador');
    }
}
