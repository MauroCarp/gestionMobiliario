<?php

namespace App\Filament\Resources\OrdenProduccionResource\Pages;

use App\Filament\Resources\OrdenProduccionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrdenProduccion extends CreateRecord
{
    protected static string $resource = OrdenProduccionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
