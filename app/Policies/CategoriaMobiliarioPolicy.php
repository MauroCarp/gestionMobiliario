<?php

namespace App\Policies;

use App\Models\CategoriaMobiliario;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;
use App\Policies\Concerns\CanViewPanelResources;

class CategoriaMobiliarioPolicy
{
    use AdministradorOnlyMutations;
    use CanViewPanelResources;

    public function viewAny(User $user): bool
    {
        return $this->canViewResource($user);
    }

    public function view(User $user, CategoriaMobiliario $categoriaMobiliario): bool
    {
        return $this->canViewResource($user);
    }
}
