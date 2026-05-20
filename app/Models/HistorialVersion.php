<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialVersion extends Model
{
    protected $table = 'historial_versiones';

    protected $fillable = [
        'mobiliario_id',
        'version',
        'descripcion_cambio',
        'snapshot',
        'user_id',
    ];

    protected $casts = [
        'version'  => 'integer',
        'snapshot' => 'array',
    ];

    public function mobiliario(): BelongsTo
    {
        return $this->belongsTo(Mobiliario::class, 'mobiliario_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
