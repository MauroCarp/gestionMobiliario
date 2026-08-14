<?php

namespace App\Policies;

use App\Models\Proveedor;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;
use App\Policies\Concerns\CanViewPanelResources;

class ProveedorPolicy
{
    use AdministradorOnlyMutations;
    use CanViewPanelResources;

    public function viewAny(User $user): bool
    {
        return $this->canViewResource($user);
    }

    public function view(User $user, Proveedor $proveedor): bool
    {
        return $this->canViewResource($user);
    }
}
