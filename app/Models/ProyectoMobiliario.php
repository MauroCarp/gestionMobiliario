<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProyectoMobiliario extends Model
{
    protected $table = 'proyecto_mobiliario';

    protected $fillable = [
        'proyecto_id',
        'mobiliario_id',
        'cantidad',
        'observaciones',
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function mobiliario(): BelongsTo
    {
        return $this->belongsTo(Mobiliario::class, 'mobiliario_id');
    }
}
