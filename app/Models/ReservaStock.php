<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservaStock extends Model
{
    protected $table = 'reservas_stock';

    protected $fillable = [
        'presupuesto_id',
        'insumo_id',
        'cantidad_reservada',
        'estado',
    ];

    protected $casts = [
        'cantidad_reservada' => 'float',
    ];

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class);
    }

    public function scopeActiva($query)
    {
        return $query->where('estado', 'activa');
    }
}
