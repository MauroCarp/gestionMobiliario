<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdenProduccionItem extends Model
{
    protected $table = 'orden_produccion_items';

    const ESTADOS = [
        'pendiente' => 'Pendiente',
        'en_proceso' => 'En proceso',
        'completada' => 'Completada',
    ];

    const ESTADO_COLORS = [
        'pendiente' => 'gray',
        'en_proceso' => 'warning',
        'completada' => 'success',
    ];

    protected $fillable = [
        'orden_produccion_id',
        'marca_id',
        'mobiliario_id',
        'cantidad',
        'cantidad_ingresada',
        'estado',
        'version_composicion',
        'finalizado_at',
        'observaciones',
        'insumos_seleccionados',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'cantidad_ingresada' => 'integer',
        'version_composicion' => 'integer',
        'finalizado_at' => 'datetime',
        'insumos_seleccionados' => 'array',
    ];

    public function ordenProduccion(): BelongsTo
    {
        return $this->belongsTo(OrdenProduccion::class);
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function mobiliario(): BelongsTo
    {
        return $this->belongsTo(Mobiliario::class);
    }

    public function insumos(): HasMany
    {
        return $this->hasMany(OrdenProduccionItemInsumo::class);
    }

    public function etapas(): HasMany
    {
        return $this->hasMany(OrdenProduccionItemEtapa::class, 'orden_produccion_item_id')
            ->orderBy('orden');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(OrdenProduccionMovimiento::class);
    }

    public function cantidadPendiente(): int
    {
        return max(0, (int) $this->cantidad - (int) $this->cantidad_ingresada);
    }

    public function estaCompletado(): bool
    {
        return $this->estado === 'completada' || $this->cantidadPendiente() <= 0;
    }

    public function puedeRecibirIngreso(): bool
    {
        return $this->cantidadPendiente() > 0
            && $this->ordenProduccion?->puedeRegistrarIngreso();
    }

    public function getItemNombreAttribute(): string
    {
        $this->loadMissing('mobiliario', 'marca');

        $nombre = $this->mobiliario?->nombre ?? 'Mobiliario';
        $marca = $this->marca?->nombre;

        return $marca ? "{$nombre} ({$marca})" : $nombre;
    }

    /**
     * @return list<int>|null
     */
    public function idsInsumosSeleccionados(): ?array
    {
        if ($this->insumos_seleccionados === null) {
            return null;
        }

        return array_values(array_map('intval', $this->insumos_seleccionados));
    }
}
