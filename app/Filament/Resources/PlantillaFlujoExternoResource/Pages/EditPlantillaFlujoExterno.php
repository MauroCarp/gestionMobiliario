<?php

namespace App\Filament\Resources\PlantillaFlujoExternoResource\Pages;

use App\Filament\Resources\PlantillaFlujoExternoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlantillaFlujoExterno extends EditRecord
{
    protected static string $resource = PlantillaFlujoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
