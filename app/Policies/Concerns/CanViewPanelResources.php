<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait CanViewPanelResources
{
    protected function canViewResource(User $user): bool
    {
        return $user->hasAnyRole([
            'Administrador',
            'Ventas',
            'Producción',
            'Depósito / Stock',
            'Solo lectura',
        ]);
    }
}
