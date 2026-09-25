<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenProduccionItemInsumo extends Model
{
    protected $table = 'orden_produccion_item_insumos';

    protected $fillable = [
        'orden_produccion_item_id',
        'insumo_id',
        'cantidad_unitaria',
        'cantidad_total',
        'cantidad_consumida',
    ];

    protected $casts = [
        'cantidad_unitaria' => 'float',
        'cantidad_total' => 'float',
        'cantidad_consumida' => 'float',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(OrdenProduccionItem::class, 'orden_produccion_item_id');
    }

    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class);
    }

    public function cantidadPendiente(): float
    {
        return max(0, (float) $this->cantidad_total - (float) $this->cantidad_consumida);
    }
}
