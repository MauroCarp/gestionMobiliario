<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PresupuestoItem extends Model
{
    protected $table = 'presupuesto_items';

    protected $fillable = [
        'presupuesto_id',
        'mobiliario_id',
        'insumo_id',
        'sector_id',
        'cantidad',
        'precio_unitario',
        'descripcion_override',
        'observaciones',
        'notas_manuales',
        'orden',
        'finalizado_at',
        'finalizado_por',
        'entregado_at',
        'entregado_por',
        'entrega_observaciones',
    ];

    protected $attributes = [
        'orden'    => 0,
        'cantidad' => 1,
    ];

    protected $casts = [
        'cantidad'        => 'integer',
        'precio_unitario' => 'decimal:2',
        'orden'           => 'integer',
        'finalizado_at'   => 'datetime',
        'entregado_at'    => 'datetime',
    ];

    public function getSubtotalAttribute(): ?float
    {
        if ($this->precio_unitario === null) {
            return null;
        }
        return round((float) $this->cantidad * (float) $this->precio_unitario, 2);
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function mobiliario(): BelongsTo
    {
        return $this->belongsTo(Mobiliario::class);
    }

    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function finalizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalizado_por');
    }

    public function entregadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entregado_por');
    }

    public function etapasProduccion(): HasMany
    {
        return $this->hasMany(PresupuestoItemEtapa::class)->orderBy('orden');
    }

    /** Retorna el nombre del ítem independientemente de si es Mobiliario o Insumo. */
    public function getItemNombreAttribute(): string
    {
        return $this->mobiliario?->nombre ?? $this->insumo?->nombre ?? '—';
    }

    /** Retorna el código del ítem independientemente de si es Mobiliario o Insumo. */
    public function getItemCodigoAttribute(): string
    {
        return $this->mobiliario?->codigo_interno ?? $this->insumo?->codigo ?? '—';
    }

    public function estaFinalizado(): bool
    {
        return ! is_null($this->finalizado_at);
    }

    public function estaEntregado(): bool
    {
        return ! is_null($this->entregado_at);
    }

    public function getEstadoEntregaAttribute(): string
    {
        return $this->estaEntregado() ? 'Entregado' : 'Pendiente';
    }

    public function getProgresoProduccionAttribute(): string
    {
        if (! $this->mobiliario_id) {
            return '—';
        }

        $etapas = $this->relationLoaded('etapasProduccion')
            ? $this->etapasProduccion
            : $this->etapasProduccion()->get();

        if ($etapas->isEmpty()) {
            return '0/0';
        }

        return $etapas->where('estado', 'completado')->count() . '/' . $etapas->count();
    }

    public function getEtapaActualProduccionAttribute(): string
    {
        if (! $this->mobiliario_id) {
            return '—';
        }

        $etapas = $this->relationLoaded('etapasProduccion')
            ? $this->etapasProduccion
            : $this->etapasProduccion()->get();

        return $etapas->firstWhere('estado', '!=', 'completado')?->nombre
            ?? ($etapas->isNotEmpty() ? 'Completado' : 'Sin etapas');
    }

    /**
     * Demanda de insumos para esta línea: [insumo_id => cantidad].
     */
    public function demandaInsumos(): array
    {
        if ($this->insumo_id) {
            return [$this->insumo_id => (float) $this->cantidad];
        }

        if (! $this->mobiliario_id) {
            return [];
        }

        $this->loadMissing('mobiliario.composicionTecnica');

        $demanda = [];

        foreach ($this->mobiliario?->composicionTecnica ?? [] as $comp) {
            $demanda[$comp->insumo_id] = ($demanda[$comp->insumo_id] ?? 0)
                + ($comp->cantidad * (int) $this->cantidad);
        }

        return $demanda;
    }
}
