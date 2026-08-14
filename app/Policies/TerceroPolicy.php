<?php

namespace App\Policies;

use App\Models\Tercero;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;
use App\Policies\Concerns\CanViewPanelResources;

class TerceroPolicy
{
    use AdministradorOnlyMutations;
    use CanViewPanelResources;

    public function viewAny(User $user): bool
    {
        return $this->canViewResource($user);
    }

    public function view(User $user, Tercero $tercero): bool
    {
        return $this->canViewResource($user);
    }
}
