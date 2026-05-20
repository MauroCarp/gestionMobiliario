<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresupuestoVersion extends Model
{
    protected $table = 'presupuesto_versiones';

    protected $fillable = [
        'presupuesto_id',
        'numero_version',
        'snapshot',
        'motivo_cambio',
        'creado_por',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
