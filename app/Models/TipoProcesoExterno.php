<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoProcesoExterno extends Model
{
    protected $table = 'tipos_proceso_externo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'color',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function plantillaEtapas(): HasMany
    {
        return $this->hasMany(PlantillaEtapa::class, 'tipo_proceso_id');
    }

    public function loteEtapas(): HasMany
    {
        return $this->hasMany(LoteEtapa::class, 'tipo_proceso_id');
    }
}
