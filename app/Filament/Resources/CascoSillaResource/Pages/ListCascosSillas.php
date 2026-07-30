<?php

namespace App\Filament\Resources\CascoSillaResource\Pages;

use App\Filament\Resources\CascoSillaResource;
use Filament\Resources\Pages\ListRecords;

class ListCascosSillas extends ListRecords
{
    protected static string $resource = CascoSillaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
