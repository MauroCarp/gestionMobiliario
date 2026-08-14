<?php

namespace App\Policies;

use App\Models\LoteProcesoExterno;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;
use App\Policies\Concerns\CanViewPanelResources;

class LoteProcesoExternoPolicy
{
    use AdministradorOnlyMutations;
    use CanViewPanelResources;

    public function viewAny(User $user): bool
    {
        return $this->canViewResource($user);
    }

    public function view(User $user, LoteProcesoExterno $loteProcesoExterno): bool
    {
        return $this->canViewResource($user);
    }
}
