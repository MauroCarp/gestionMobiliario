<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;

class UserPolicy
{
    use AdministradorOnlyMutations;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Administrador');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasRole('Administrador') || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('Administrador') && $user->id !== $model->id;
    }
}
