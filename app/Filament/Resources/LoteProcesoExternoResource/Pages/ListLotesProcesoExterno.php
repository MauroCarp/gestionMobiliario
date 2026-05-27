<?php

namespace App\Filament\Resources\LoteProcesoExternoResource\Pages;

use App\Filament\Resources\LoteProcesoExternoResource;
use Filament\Resources\Pages\ListRecords;

class ListLotesProcesoExterno extends ListRecords
{
    protected static string $resource = LoteProcesoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
