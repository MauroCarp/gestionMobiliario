<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait AdministradorOnlyMutations
{
    public function create(User $user): bool
    {
        return $user->hasRole('Administrador');
    }

    public function update(User $user, mixed $model): bool
    {
        return $user->hasRole('Administrador');
    }

    public function delete(User $user, mixed $model): bool
    {
        return $user->hasRole('Administrador');
    }

    public function restore(User $user, mixed $model): bool
    {
        return $user->hasRole('Administrador');
    }

    public function forceDelete(User $user, mixed $model): bool
    {
        return $user->hasRole('Administrador');
    }
}
