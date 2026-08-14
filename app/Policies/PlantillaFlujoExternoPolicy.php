<?php

namespace App\Policies;

use App\Models\PlantillaFlujoExterno;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;
use App\Policies\Concerns\CanViewPanelResources;

class PlantillaFlujoExternoPolicy
{
    use AdministradorOnlyMutations;
    use CanViewPanelResources;

    public function viewAny(User $user): bool
    {
        return $this->canViewResource($user);
    }

    public function view(User $user, PlantillaFlujoExterno $plantillaFlujoExterno): bool
    {
        return $this->canViewResource($user);
    }
}
