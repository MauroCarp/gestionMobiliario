<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenProduccionItemEtapa extends Model
{
    protected $table = 'orden_produccion_item_etapas';

    const ESTADOS = [
        'pendiente' => 'Pendiente',
        'en_proceso' => 'En proceso',
        'completado' => 'Completado',
    ];

    const ESTADOS_INICIO = [
        'pendiente' => 'Pendiente',
        'completado' => 'Completado',
    ];

    const ESTADO_COLORS = [
        'pendiente' => 'gray',
        'en_proceso' => 'warning',
        'completado' => 'success',
    ];

    protected $fillable = [
        'orden_produccion_item_id',
        'nombre',
        'orden',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'iniciado_por',
        'completado_por',
        'observaciones',
    ];

    protected $casts = [
        'orden' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(OrdenProduccionItem::class, 'orden_produccion_item_id');
    }

    public function iniciadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iniciado_por');
    }

    public function completadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completado_por');
    }
}
