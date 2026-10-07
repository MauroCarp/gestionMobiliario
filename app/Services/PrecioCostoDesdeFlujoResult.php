<?php

namespace App\Services;

use App\Models\Insumo;

class PrecioCostoDesdeFlujoResult
{
    public const ACTUALIZABLE = 'actualizable';

    public const SIN_CAMBIO = 'sin_cambio';

    public const SIN_PRECIOS = 'sin_precios';

    public const REVISION_MANUAL = 'revision_manual';

    public const SIN_PLANTILLAS = 'sin_plantillas';

    public const INSUMO_NO_ENCONTRADO = 'insumo_no_encontrado';

    /**
     * @param  list<string>  $plantillaNombres
     */
    public function __construct(
        public readonly string $estado,
        public readonly ?Insumo $insumo = null,
        public readonly ?float $precioActual = null,
        public readonly ?float $nuevoPrecio = null,
        public readonly ?string $plantillaNombre = null,
        public readonly array $plantillaNombres = [],
    ) {}

    public function debeActualizar(): bool
    {
        return $this->estado === self::ACTUALIZABLE
            && $this->insumo !== null
            && $this->nuevoPrecio !== null;
    }
}
