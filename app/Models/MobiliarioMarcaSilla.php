<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobiliarioMarcaSilla extends Model
{
    protected $table = 'mobiliario_marcas_silla';

    protected $fillable = [
        'mobiliario_id',
        'marca_id',
        'nombre_fantasia',
    ];

    public function mobiliario(): BelongsTo
    {
        return $this->belongsTo(Mobiliario::class, 'mobiliario_id');
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }
}
