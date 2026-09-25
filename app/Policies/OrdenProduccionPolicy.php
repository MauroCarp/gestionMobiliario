<?php

namespace App\Policies;

use App\Models\OrdenProduccion;
use App\Models\User;
use App\Policies\Concerns\CanViewPanelResources;

class OrdenProduccionPolicy
{
    use CanViewPanelResources;

    public function viewAny(User $user): bool
    {
        return $this->canViewResource($user);
    }

    public function view(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $this->canViewResource($user);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Producción']);
    }

    public function update(User $user, OrdenProduccion $ordenProduccion): bool
    {
        if (! $ordenProduccion->puedeEditar()) {
            return false;
        }

        return $user->hasAnyRole(['Administrador', 'Producción']);
    }

    public function delete(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $user->hasRole('Administrador') && $ordenProduccion->puedeEditar();
    }

    public function restore(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $user->hasRole('Administrador');
    }

    public function forceDelete(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $user->hasRole('Administrador');
    }

    public function start(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $this->puedeOperarProduccion($user) && $ordenProduccion->puedeIniciar();
    }

    public function pause(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $this->puedeOperarProduccion($user) && $ordenProduccion->puedePausar();
    }

    public function resume(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $this->puedeOperarProduccion($user) && $ordenProduccion->puedeReanudar();
    }

    public function cancel(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $this->puedeOperarProduccion($user) && $ordenProduccion->puedeCancelar();
    }

    public function registerProduction(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $this->puedeOperarProduccion($user) && $ordenProduccion->puedeRegistrarIngreso();
    }

    public function manageItemStages(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $this->puedeOperarProduccion($user) && $ordenProduccion->puedeGestionarEtapas();
    }

    private function puedeOperarProduccion(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Producción']);
    }
}
