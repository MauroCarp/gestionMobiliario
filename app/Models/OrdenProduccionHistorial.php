<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenProduccionHistorial extends Model
{
    protected $table = 'orden_produccion_historial';

    protected $fillable = [
        'orden_produccion_id',
        'estado_anterior',
        'estado_nuevo',
        'comentario',
        'user_id',
    ];

    public function ordenProduccion(): BelongsTo
    {
        return $this->belongsTo(OrdenProduccion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
