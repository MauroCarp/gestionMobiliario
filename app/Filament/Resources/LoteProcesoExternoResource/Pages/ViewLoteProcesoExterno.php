<?php

namespace App\Filament\Resources\LoteProcesoExternoResource\Pages;

use App\Filament\Resources\LoteProcesoExternoResource;
use App\Filament\Resources\LoteProcesoExternoResource\RelationManagers\EtapasRelationManager;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewLoteProcesoExterno extends ViewRecord
{
    protected static string $resource = LoteProcesoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('cancelar')
                ->label('Cancelar lote')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn () => ! in_array($this->record->estado, ['completado', 'cancelado']))
                ->action(function (): void {
                    $this->record->update(['estado' => 'cancelado']);
                    Notification::make()->title('Lote cancelado')->warning()->send();
                    $this->refreshFormData(['estado']);
                }),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            EtapasRelationManager::class,
        ];
    }
}
