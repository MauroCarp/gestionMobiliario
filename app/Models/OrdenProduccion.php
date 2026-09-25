<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrdenProduccion extends Model
{
    use LogsActivity;

    protected $table = 'ordenes_produccion';

    const ESTADOS = [
        'borrador' => 'Borrador',
        'en_proceso' => 'En proceso',
        'pausada' => 'Pausada',
        'completada' => 'Completada',
        'cancelada' => 'Cancelada',
    ];

    const ESTADO_COLORS = [
        'borrador' => 'gray',
        'en_proceso' => 'info',
        'pausada' => 'warning',
        'completada' => 'success',
        'cancelada' => 'danger',
    ];

    protected $fillable = [
        'codigo',
        'estado',
        'fecha_inicio',
        'iniciado_at',
        'iniciado_por',
        'completado_at',
        'cancelado_at',
        'cancelado_por',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'iniciado_at' => 'datetime',
        'completado_at' => 'datetime',
        'cancelado_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (OrdenProduccion $orden): void {
            if (empty($orden->codigo)) {
                $year = now()->year;
                $ultimoCodigo = static::query()
                    ->where('codigo', 'like', "OP-{$year}-%")
                    ->orderByDesc('codigo')
                    ->value('codigo');

                $ultimoNumero = $ultimoCodigo && preg_match('/^OP-\d{4}-(\d+)$/', $ultimoCodigo, $m)
                    ? (int) $m[1]
                    : 0;

                $orden->codigo = sprintf('OP-%d-%04d', $year, $ultimoNumero + 1);
            }

            if (empty($orden->estado)) {
                $orden->estado = 'borrador';
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrdenProduccionItem::class);
    }

    public function historial(): HasMany
    {
        return $this->hasMany(OrdenProduccionHistorial::class)->latest();
    }

    public function reservasStock(): HasMany
    {
        return $this->hasMany(ReservaStock::class);
    }

    public function ordenesCompra(): HasMany
    {
        return $this->hasMany(OrdenCompra::class);
    }

    public function lotesProcesoExterno(): HasMany
    {
        return $this->hasMany(LoteProcesoExterno::class, 'origen_id')
            ->where('origen_tipo', 'orden_produccion');
    }

    public function movimientos(): HasManyThrough
    {
        return $this->hasManyThrough(
            OrdenProduccionMovimiento::class,
            OrdenProduccionItem::class,
            'orden_produccion_id',
            'orden_produccion_item_id',
        );
    }

    public function iniciadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iniciado_por');
    }

    public function canceladoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelado_por');
    }

    public function puedeEditar(): bool
    {
        return $this->estado === 'borrador';
    }

    public function puedeIniciar(): bool
    {
        return $this->estado === 'borrador';
    }

    public function puedePausar(): bool
    {
        return $this->estado === 'en_proceso';
    }

    public function puedeReanudar(): bool
    {
        return $this->estado === 'pausada';
    }

    public function puedeCancelar(): bool
    {
        return in_array($this->estado, ['borrador', 'en_proceso', 'pausada'], true);
    }

    public function puedeRegistrarIngreso(): bool
    {
        return $this->estado === 'en_proceso';
    }

    public function puedeGestionarEtapas(): bool
    {
        return in_array($this->estado, ['en_proceso', 'pausada'], true);
    }

    public function cantidadTotal(): int
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();

        return (int) $items->sum('cantidad');
    }

    public function cantidadIngresada(): int
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();

        return (int) $items->sum('cantidad_ingresada');
    }

    public function estaCompletada(): bool
    {
        return $this->estado === 'completada';
    }
}
