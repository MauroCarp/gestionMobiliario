<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenCompraItemRecepcion extends Model
{
    protected $table = 'orden_compra_item_recepciones';

    protected $fillable = [
        'orden_compra_item_id',
        'cantidad',
        'fecha_recepcion',
        'notas',
    ];

    protected $casts = [
        'cantidad'         => 'float',
        'fecha_recepcion'  => 'date',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(OrdenCompraItem::class, 'orden_compra_item_id');
    }
}
