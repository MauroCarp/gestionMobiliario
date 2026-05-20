<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdenCompra extends Model
{
    protected $table = 'ordenes_compra';

    const ESTADOS = [
        'sugerida'  => 'Sugerida',
        'pendiente' => 'Pendiente',
        'aprobada'  => 'Aprobada',
        'recibida'  => 'Recibida',
        'cancelada' => 'Cancelada',
    ];

    const PRIORIDADES = [
        'baja'    => 'Baja',
        'media'   => 'Media',
        'alta'    => 'Alta',
        'critica' => 'Crítica',
    ];

    const ESTADO_COLORS = [
        'sugerida'  => 'gray',
        'pendiente' => 'warning',
        'aprobada'  => 'info',
        'recibida'  => 'success',
        'cancelada' => 'danger',
    ];

    const PRIORIDAD_COLORS = [
        'baja'    => 'gray',
        'media'   => 'info',
        'alta'    => 'warning',
        'critica' => 'danger',
    ];

    protected $fillable = [
        'codigo',
        'estado',
        'prioridad',
        'generado_automaticamente',
        'observaciones',
        'presupuesto_id',
    ];

    protected $casts = [
        'generado_automaticamente' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (OrdenCompra $orden): void {
            if (empty($orden->codigo)) {
                $year  = now()->year;
                $count = static::whereYear('created_at', $year)->count() + 1;
                $orden->codigo = sprintf('OC-%d-%04d', $year, $count);
            }
        });
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrdenCompraItem::class);
    }

    public function getTotalEstimadoAttribute(): float
    {
        return $this->items->sum(fn ($i) =>
            ($i->precio_unitario ?? 0) * $i->cantidad_solicitada
        );
    }
}
