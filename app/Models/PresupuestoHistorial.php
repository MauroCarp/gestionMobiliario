<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresupuestoHistorial extends Model
{
    protected $table = 'presupuesto_historial';

    protected $fillable = [
        'presupuesto_id',
        'estado_anterior',
        'estado_nuevo',
        'comentario',
        'user_id',
    ];

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
