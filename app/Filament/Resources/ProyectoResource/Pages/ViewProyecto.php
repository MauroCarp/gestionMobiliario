<?php

namespace App\Filament\Resources\ProyectoResource\Pages;

use App\Filament\Resources\ProyectoResource;
use App\Filament\Resources\ProyectoResource\RelationManagers\AgenciasRelationManager;
use App\Filament\Resources\ProyectoResource\RelationManagers\HistorialRelationManager;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProyecto extends ViewRecord
{
    protected static string $resource = ProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            ProyectoResource::verManualesPageAction(fn () => $this->record),
            Actions\DeleteAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            AgenciasRelationManager::class,
            HistorialRelationManager::class,
        ];
    }
}
