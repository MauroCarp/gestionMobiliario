<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Support\FilamentResourceVisibility;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->visible_resources === null && ! $this->record->hasRole('Administrador')) {
            $data['visible_resources'] = FilamentResourceVisibility::resolveDefaultVisibleResourcesForUser($this->record);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return UserResource::normalizeVisibleResourcesData($data);
    }
}
