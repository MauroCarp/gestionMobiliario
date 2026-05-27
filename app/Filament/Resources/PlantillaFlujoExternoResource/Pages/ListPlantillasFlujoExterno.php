<?php

namespace App\Filament\Resources\PlantillaFlujoExternoResource\Pages;

use App\Filament\Resources\PlantillaFlujoExternoResource;
use Filament\Resources\Pages\ListRecords;

class ListPlantillasFlujoExterno extends ListRecords
{
    protected static string $resource = PlantillaFlujoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
