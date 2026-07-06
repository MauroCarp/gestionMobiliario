<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresupuestoItemEtapa extends Model
{
    protected $table = 'presupuesto_item_etapas';

    const ESTADOS = [
        'pendiente'  => 'Pendiente',
        'en_proceso' => 'En proceso',
        'completado' => 'Completado',
    ];

    const ESTADOS_INICIO = [
        'pendiente'  => 'Pendiente',
        'completado' => 'Completado',
    ];

    const ESTADO_COLORS = [
        'pendiente'  => 'gray',
        'en_proceso' => 'warning',
        'completado' => 'success',
    ];

    protected $fillable = [
        'presupuesto_item_id',
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
        'orden'        => 'integer',
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    public function presupuestoItem(): BelongsTo
    {
        return $this->belongsTo(PresupuestoItem::class);
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
