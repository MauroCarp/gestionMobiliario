<?php

namespace App\Filament\Resources\OrdenProduccionResource\Pages;

use App\Filament\Resources\OrdenProduccionResource;
use App\Models\OrdenProduccion;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrdenProduccion extends EditRecord
{
    protected static string $resource = OrdenProduccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function authorizeAccess(): void
    {
        parent::authorizeAccess();

        /** @var OrdenProduccion $record */
        $record = $this->getRecord();

        abort_unless($record->puedeEditar(), 403);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
