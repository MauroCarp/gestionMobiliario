<?php

namespace App\Filament\Resources\MobiliarioResource\Pages;

use App\Filament\Resources\MobiliarioResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditMobiliario extends EditRecord
{
    protected static string $resource = MobiliarioResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make(), Actions\RestoreAction::make()];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getDuplicarFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getDuplicarFormAction(): Action
    {
        return Action::make('duplicar')
            ->label('Duplicar')
            ->icon('heroicon-o-document-duplicate')
            ->color('gray')
            ->visible(fn (): bool => MobiliarioResource::canCreate())
            ->action(function (): void {
                $data = $this->data ?? [];
                unset($data['codigo_interno'], $data['imagenes'], $data['documentos']);

                session([
                    'mobiliario.duplicate_data' => $data,
                    'mobiliario.duplicate_from_id' => $this->getRecord()->getKey(),
                ]);

                $this->redirect(MobiliarioResource::getUrl('create'));
            });
    }
}
