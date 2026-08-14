<?php

namespace App\Policies;

use App\Models\OrdenCompra;
use App\Models\User;
use App\Policies\Concerns\AdministradorOnlyMutations;
use App\Policies\Concerns\CanViewPanelResources;

class OrdenCompraPolicy
{
    use AdministradorOnlyMutations;
    use CanViewPanelResources;

    public function viewAny(User $user): bool
    {
        return $this->canViewResource($user);
    }

    public function view(User $user, OrdenCompra $ordenCompra): bool
    {
        return $this->canViewResource($user);
    }
}
