<?php

namespace App\Filament\Resources\TipoProcesoExternoResource\Pages;

use App\Filament\Resources\TipoProcesoExternoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoProcesoExterno extends EditRecord
{
    protected static string $resource = TipoProcesoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
