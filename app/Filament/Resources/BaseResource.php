<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Support\FilamentResourceVisibility;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

abstract class BaseResource extends Resource
{
    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        $key = FilamentResourceVisibility::keyForResourceClass(static::class);

        if ($key !== null && ! $user->canViewFilamentResource($key)) {
            return false;
        }

        return parent::canViewAny();
    }

    public static function canView(Model $record): bool
    {
        if (! static::canViewAny()) {
            return false;
        }

        return parent::canView($record);
    }
}
