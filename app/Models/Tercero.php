<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tercero extends Model
{
    use SoftDeletes;

    protected $table = 'terceros';

    const TIPOS = [
        'proveedor_material' => 'Proveedor de material',
        'servicio_externo'   => 'Servicio externo',
    ];

    const TIPO_COLORS = [
        'proveedor_material' => 'info',
        'servicio_externo'   => 'warning',
    ];

    protected $fillable = [
        'nombre',
        'tipo',
        'especialidad',
        'telefono',
        'email',
        'contacto',
        'activo',
        'observaciones',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function plantillaEtapas(): HasMany
    {
        return $this->hasMany(PlantillaEtapa::class, 'tercero_id');
    }

    public function loteEtapas(): HasMany
    {
        return $this->hasMany(LoteEtapa::class, 'tercero_id');
    }
}
