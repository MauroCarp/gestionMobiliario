<?php

namespace App\Filament\Resources\MobiliarioResource\Pages;

use App\Filament\Resources\MobiliarioResource;
use App\Models\Mobiliario;
use Filament\Resources\Pages\CreateRecord;

class CreateMobiliario extends CreateRecord
{
    protected static string $resource = MobiliarioResource::class;

    protected ?int $duplicateFromId = null;

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->duplicateFromId = session()->pull('mobiliario.duplicate_from_id');
        $duplicateData = session()->pull('mobiliario.duplicate_data');

        $this->fillForm();

        if ($duplicateData) {
            unset($duplicateData['codigo_interno'], $duplicateData['imagenes'], $duplicateData['documentos']);
            $this->form->fill($duplicateData);
        }

        $this->previousUrl = url()->previous();
    }

    protected function afterCreate(): void
    {
        if (! $this->duplicateFromId) {
            return;
        }

        $source = Mobiliario::find($this->duplicateFromId);

        if (! $source) {
            return;
        }

        foreach ($source->getMedia('imagenes') as $media) {
            $media->copy($this->record, 'imagenes');
        }

        foreach ($source->getMedia('documentos') as $media) {
            $media->copy($this->record, 'documentos');
        }
    }
}
