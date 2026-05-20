<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenCompraItem extends Model
{
    protected $table = 'orden_compra_items';

    protected $fillable = [
        'orden_compra_id',
        'insumo_id',
        'cantidad_solicitada',
        'cantidad_recibida',
        'precio_unitario',
        'observaciones',
    ];

    protected $casts = [
        'cantidad_solicitada' => 'float',
        'cantidad_recibida'   => 'float',
        'precio_unitario'     => 'float',
    ];

    public function ordenCompra(): BelongsTo
    {
        return $this->belongsTo(OrdenCompra::class);
    }

    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class);
    }

    public function getPendienteAttribute(): float
    {
        return max(0, $this->cantidad_solicitada - $this->cantidad_recibida);
    }
}
