<?php

namespace App\Observers;

use App\Models\PlantillaEtapa;
use App\Services\PrecioCostoDesdeFlujoService;

class PlantillaEtapaObserver
{
    public function __construct(
        private readonly PrecioCostoDesdeFlujoService $precioCostoDesdeFlujo,
    ) {}

    public function saved(PlantillaEtapa $etapa): void
    {
        $this->sincronizarInsumo($etapa);
    }

    public function deleted(PlantillaEtapa $etapa): void
    {
        $this->sincronizarInsumo($etapa);
    }

    private function sincronizarInsumo(PlantillaEtapa $etapa): void
    {
        $plantilla = $etapa->plantilla;

        if ($plantilla === null || $plantilla->entidad_tipo !== 'insumo' || $plantilla->entidad_id === null) {
            return;
        }

        $this->precioCostoDesdeFlujo->sincronizar((int) $plantilla->entidad_id);
    }
}
