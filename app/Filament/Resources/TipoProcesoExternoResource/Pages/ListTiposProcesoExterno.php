<?php

namespace App\Filament\Resources\TipoProcesoExternoResource\Pages;

use App\Filament\Resources\TipoProcesoExternoResource;
use Filament\Resources\Pages\ListRecords;

class ListTiposProcesoExterno extends ListRecords
{
    protected static string $resource = TipoProcesoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
