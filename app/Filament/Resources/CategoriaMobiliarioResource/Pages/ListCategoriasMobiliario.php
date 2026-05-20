<?php

namespace App\Filament\Resources\CategoriaMobiliarioResource\Pages;

use App\Filament\Resources\CategoriaMobiliarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoriasMobiliario extends ListRecords
{
    protected static string $resource = CategoriaMobiliarioResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
