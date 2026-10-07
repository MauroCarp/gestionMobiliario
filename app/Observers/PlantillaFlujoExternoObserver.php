<?php

namespace App\Observers;

use App\Models\PlantillaFlujoExterno;
use App\Services\PrecioCostoDesdeFlujoService;

class PlantillaFlujoExternoObserver
{
    public function __construct(
        private readonly PrecioCostoDesdeFlujoService $precioCostoDesdeFlujo,
    ) {}

    public function updated(PlantillaFlujoExterno $plantilla): void
    {
        if (! $plantilla->wasChanged(['entidad_tipo', 'entidad_id'])) {
            return;
        }

        $this->sincronizarSiInsumo(
            $plantilla->getOriginal('entidad_tipo'),
            $plantilla->getOriginal('entidad_id'),
        );
        $this->sincronizarSiInsumo($plantilla->entidad_tipo, $plantilla->entidad_id);
    }

    public function deleted(PlantillaFlujoExterno $plantilla): void
    {
        $this->sincronizarSiInsumo($plantilla->entidad_tipo, $plantilla->entidad_id);
    }

    private function sincronizarSiInsumo(?string $entidadTipo, mixed $entidadId): void
    {
        if ($entidadTipo !== 'insumo' || $entidadId === null) {
            return;
        }

        $this->precioCostoDesdeFlujo->sincronizar((int) $entidadId);
    }
}
