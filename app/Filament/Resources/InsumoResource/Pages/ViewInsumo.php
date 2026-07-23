<?php

namespace App\Filament\Resources\InsumoResource\Pages;

use App\Filament\Resources\InsumoResource;
use App\Filament\Resources\InsumoResource\RelationManagers\PlantillaFlujosRelationManager;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInsumo extends ViewRecord
{
    protected static string $resource = InsumoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            InsumoResource::verImagenPageAction(fn () => $this->record),
            InsumoResource::verPlanoPageAction(fn () => $this->record),
            Actions\DeleteAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            PlantillaFlujosRelationManager::class,
        ];
    }
}
