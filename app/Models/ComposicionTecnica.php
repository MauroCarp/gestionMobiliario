<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComposicionTecnica extends Model
{
    protected $table = 'composicion_tecnica';

    protected $fillable = [
        'mobiliario_id',
        'insumo_id',
        'cantidad',
        'version',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'cantidad' => 'float',
        'version'  => 'integer',
        'activo'   => 'boolean',
    ];

    public function mobiliario(): BelongsTo
    {
        return $this->belongsTo(Mobiliario::class, 'mobiliario_id');
    }

    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
}
