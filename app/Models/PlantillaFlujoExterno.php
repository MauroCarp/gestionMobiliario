<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlantillaFlujoExterno extends Model
{
    protected $table = 'plantillas_flujo_externo';

    const ENTIDAD_TIPOS = [
        'insumo'     => 'Insumo',
        'mobiliario' => 'Mobiliario',
    ];

    protected $fillable = [
        'nombre',
        'entidad_tipo',
        'entidad_id',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function etapas(): HasMany
    {
        return $this->hasMany(PlantillaEtapa::class, 'plantilla_id')->orderBy('orden');
    }

    public function lotes(): HasMany
    {
        return $this->hasMany(LoteProcesoExterno::class, 'plantilla_id');
    }

    public function getEntidadNombreAttribute(): string
    {
        return match ($this->entidad_tipo) {
            'insumo'     => Insumo::find($this->entidad_id)?->nombre ?? '—',
            'mobiliario' => Mobiliario::find($this->entidad_id)?->nombre ?? '—',
            default      => '—',
        };
    }
}
