<?php

namespace App\Support;

use App\Models\OrdenProduccion;
use App\Models\User;

class OrdenProduccionAuthorization
{
    public static function canForRecord(string $ability, OrdenProduccion $orden): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->can($ability, $orden);
    }
}
