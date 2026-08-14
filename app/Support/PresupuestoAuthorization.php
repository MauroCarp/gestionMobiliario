<?php

namespace App\Support;

use App\Models\Presupuesto;
use App\Models\User;

class PresupuestoAuthorization
{
    public static function can(User $user, string $ability, Presupuesto $presupuesto): bool
    {
        return $user->can($ability, $presupuesto);
    }

    public static function canForRecord(string $ability, Presupuesto $presupuesto): bool
    {
        $user = auth()->user();

        return $user instanceof User && static::can($user, $ability, $presupuesto);
    }
}
