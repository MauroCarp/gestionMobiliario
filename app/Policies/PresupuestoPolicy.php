<?php

namespace App\Policies;

use App\Models\Presupuesto;
use App\Models\User;

class PresupuestoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Solo lectura']);
    }

    public function view(User $user, Presupuesto $presupuesto): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas', 'Producción', 'Solo lectura']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Ventas']);
    }

    public function update(User $user, Presupuesto $presupuesto): bool
    {
        if (! $presupuesto->puedeEditar()) {
            return false;
        }
        return $user->hasAnyRole(['Administrador', 'Ventas']);
    }

    public function delete(User $user, Presupuesto $presupuesto): bool
    {
        return $user->hasRole('Administrador');
    }

    public function restore(User $user, Presupuesto $presupuesto): bool
    {
        return $user->hasRole('Administrador');
    }

    public function forceDelete(User $user, Presupuesto $presupuesto): bool
    {
        return $user->hasRole('Administrador');
    }
}
