<?php

namespace App\Policies;

use App\Models\UnidadMedida;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;
use App\Policies\Concerns\CanViewPanelResources;

class UnidadMedidaPolicy
{
    use AdministradorOnlyMutations;
    use CanViewPanelResources;

    public function viewAny(User $user): bool
    {
        return $this->canViewResource($user);
    }

    public function view(User $user, UnidadMedida $unidadMedida): bool
    {
        return $this->canViewResource($user);
    }
}
