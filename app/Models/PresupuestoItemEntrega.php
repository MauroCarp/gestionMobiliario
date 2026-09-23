<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresupuestoItemEntrega extends Model
{
    protected $table = 'presupuesto_item_entregas';

    protected $fillable = [
        'presupuesto_item_id',
        'cantidad',
        'entregado_at',
        'entregado_por',
        'observaciones',
        'anulado_at',
        'anulado_por',
        'motivo_anulacion',
    ];

    protected $casts = [
        'cantidad'     => 'integer',
        'entregado_at' => 'datetime',
        'anulado_at'   => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(PresupuestoItem::class, 'presupuesto_item_id');
    }

    public function entregadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entregado_por');
    }

    public function anuladoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'anulado_por');
    }

    public function estaAnulada(): bool
    {
        return ! is_null($this->anulado_at);
    }

    public function scopeActivas(Builder $query): Builder
    {
        return $query->whereNull('anulado_at');
    }
}
