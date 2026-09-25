<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenProduccionMovimiento extends Model
{
    protected $table = 'orden_produccion_movimientos';

    protected $fillable = [
        'orden_produccion_item_id',
        'cantidad',
        'registrado_at',
        'registrado_por',
        'observaciones',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'registrado_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(OrdenProduccionItem::class, 'orden_produccion_item_id');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
