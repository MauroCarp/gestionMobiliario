<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsumoMarcaSilla extends Model
{
    protected $table = 'insumo_marcas_silla';

    protected $fillable = [
        'insumo_id',
        'marca_id',
        'nombre_fantasia',
    ];

    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }
}
