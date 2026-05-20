<?php

namespace App\Filament\Resources\CategoriaMobiliarioResource\Pages;

use App\Filament\Resources\CategoriaMobiliarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoriaMobiliario extends EditRecord
{
    protected static string $resource = CategoriaMobiliarioResource::class;
    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make(), Actions\RestoreAction::make()];
    }
}
