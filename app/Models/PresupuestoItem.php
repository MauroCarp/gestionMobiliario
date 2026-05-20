<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresupuestoItem extends Model
{
    protected $table = 'presupuesto_items';

    protected $fillable = [
        'presupuesto_id',
        'mobiliario_id',
        'cantidad',
        'precio_unitario',
        'descripcion_override',
        'observaciones',
        'notas_manuales',
        'orden',
    ];

    protected $attributes = [
        'orden'    => 0,
        'cantidad' => 1,
    ];

    protected $casts = [
        'cantidad'        => 'integer',
        'precio_unitario' => 'decimal:2',
        'orden'           => 'integer',
    ];

    public function getSubtotalAttribute(): ?float
    {
        if ($this->precio_unitario === null) {
            return null;
        }
        return round((float) $this->cantidad * (float) $this->precio_unitario, 2);
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function mobiliario(): BelongsTo
    {
        return $this->belongsTo(Mobiliario::class);
    }
}
