<?php

namespace App\Filament\Resources\LoteProcesoExternoResource\Pages;

use App\Filament\Resources\LoteProcesoExternoResource;
use App\Models\PlantillaFlujoExterno;
use Filament\Resources\Pages\CreateRecord;

class CreateLoteProcesoExterno extends CreateRecord
{
    protected static string $resource = LoteProcesoExternoResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($record->plantilla_id) {
            $plantilla = PlantillaFlujoExterno::with('etapas')->find($record->plantilla_id);
            if ($plantilla) {
                $record->crearEtapasDesde($plantilla);
                $record->update([
                    'estado'       => 'en_proceso',
                    'fecha_inicio' => $record->fecha_inicio ?? now()->toDateString(),
                ]);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
