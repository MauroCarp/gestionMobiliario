<?php

namespace App\Services;

use App\Models\Insumo;
use App\Models\PlantillaEtapa;
use App\Models\PlantillaFlujoExterno;

class PrecioCostoDesdeFlujoService
{
    public function evaluar(int $insumoId): PrecioCostoDesdeFlujoResult
    {
        $insumo = Insumo::query()->find($insumoId);

        if (! $insumo) {
            return new PrecioCostoDesdeFlujoResult(
                PrecioCostoDesdeFlujoResult::INSUMO_NO_ENCONTRADO,
            );
        }

        $plantillas = PlantillaFlujoExterno::query()
            ->where('entidad_tipo', 'insumo')
            ->where('entidad_id', $insumoId)
            ->with('etapas')
            ->orderBy('id')
            ->get();

        if ($plantillas->isEmpty()) {
            return new PrecioCostoDesdeFlujoResult(
                PrecioCostoDesdeFlujoResult::SIN_PLANTILLAS,
                $insumo,
            );
        }

        if ($plantillas->count() > 1) {
            return new PrecioCostoDesdeFlujoResult(
                estado: PrecioCostoDesdeFlujoResult::REVISION_MANUAL,
                insumo: $insumo,
                plantillaNombres: $plantillas->pluck('nombre')->all(),
            );
        }

        $plantilla = $plantillas->first();
        $etapas = $plantilla->etapas;
        $tienePrecio = $etapas->contains(
            fn (PlantillaEtapa $etapa): bool => $etapa->ultimo_precio !== null
        );

        if (! $tienePrecio) {
            return new PrecioCostoDesdeFlujoResult(
                estado: PrecioCostoDesdeFlujoResult::SIN_PRECIOS,
                insumo: $insumo,
                plantillaNombre: $plantilla->nombre,
            );
        }

        $nuevoCosto = round(
            (float) $etapas->sum(fn (PlantillaEtapa $etapa): float => (float) ($etapa->ultimo_precio ?? 0)),
            2
        );
        $actual = $insumo->precio_costo === null
            ? null
            : round((float) $insumo->precio_costo, 2);

        if ($actual === $nuevoCosto) {
            return new PrecioCostoDesdeFlujoResult(
                estado: PrecioCostoDesdeFlujoResult::SIN_CAMBIO,
                insumo: $insumo,
                precioActual: $actual,
                nuevoPrecio: $nuevoCosto,
                plantillaNombre: $plantilla->nombre,
            );
        }

        return new PrecioCostoDesdeFlujoResult(
            estado: PrecioCostoDesdeFlujoResult::ACTUALIZABLE,
            insumo: $insumo,
            precioActual: $actual,
            nuevoPrecio: $nuevoCosto,
            plantillaNombre: $plantilla->nombre,
        );
    }

    public function sincronizar(int $insumoId): PrecioCostoDesdeFlujoResult
    {
        $resultado = $this->evaluar($insumoId);
        $this->aplicar($resultado);

        return $resultado;
    }

    public function aplicar(PrecioCostoDesdeFlujoResult $resultado): void
    {
        if (! $resultado->debeActualizar()) {
            return;
        }

        $resultado->insumo->update(['precio_costo' => $resultado->nuevoPrecio]);
    }
}
