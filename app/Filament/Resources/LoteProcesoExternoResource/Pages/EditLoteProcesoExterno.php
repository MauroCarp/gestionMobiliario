<?php

namespace App\Filament\Resources\LoteProcesoExternoResource\Pages;

use App\Filament\Resources\LoteProcesoExternoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoteProcesoExterno extends EditRecord
{
    protected static string $resource = LoteProcesoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
