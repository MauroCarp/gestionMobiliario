<?php

namespace App\Filament\Resources\MobiliarioResource\Pages;

use App\Filament\Resources\MobiliarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMobiliario extends ViewRecord
{
    protected static string $resource = MobiliarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
