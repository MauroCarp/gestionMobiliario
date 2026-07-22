<?php

namespace App\Filament\Resources\OrdenCompraResource\Pages;

use App\Filament\Resources\OrdenCompraResource;
use App\Filament\Resources\OrdenCompraResource\RelationManagers\ItemsRelationManager;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewOrdenCompra extends ViewRecord
{
    protected static string $resource = OrdenCompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\Action::make('aprobar')
                ->label('Aprobar')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => in_array($this->record->estado, ['sugerida', 'pendiente'], true))
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->update(['estado' => 'aprobada']);
                    Notification::make()->title('Orden aprobada')->success()->send();
                    $this->refreshFormData(['estado']);
                }),

            Actions\DeleteAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }
}
