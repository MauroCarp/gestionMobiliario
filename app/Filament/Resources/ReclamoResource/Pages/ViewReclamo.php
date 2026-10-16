<?php

namespace App\Filament\Resources\ReclamoResource\Pages;

use App\Filament\Resources\ReclamoResource;
use App\Filament\Resources\ReclamoResource\RelationManagers\RegistrosRelationManager;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReclamo extends ViewRecord
{
    protected static string $resource = ReclamoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            RegistrosRelationManager::class,
        ];
    }
}
