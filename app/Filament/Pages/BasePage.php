<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Support\FilamentResourceVisibility;
use Filament\Pages\Page;

abstract class BasePage extends Page
{
    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        $key = FilamentResourceVisibility::keyForPageClass(static::class);

        if ($key !== null && ! $user->canViewFilamentResource($key)) {
            return false;
        }

        return parent::canAccess();
    }
}
