<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdenCompra extends Model
{
    protected $table = 'ordenes_compra';

    const ESTADOS = [
        'sugerida'          => 'Sugerida',
        'pendiente'         => 'Pendiente',
        'aprobada'          => 'Aprobada',
        'recibida_parcial'  => 'Recibida Parcial',
        'recibida'          => 'Recibida',
        'cancelada'         => 'Cancelada',
    ];

    const PRIORIDADES = [
        'baja'    => 'Baja',
        'media'   => 'Media',
        'alta'    => 'Alta',
        'critica' => 'Crítica',
    ];

    const ESTADO_COLORS = [
        'sugerida'         => 'gray',
        'pendiente'        => 'warning',
        'aprobada'         => 'info',
        'recibida_parcial' => 'warning',
        'recibida'         => 'success',
        'cancelada'        => 'danger',
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
        'proveedor_id',
        'fecha_pactada_entrega',
    ];

    protected $casts = [
        'generado_automaticamente' => 'boolean',
        'fecha_pactada_entrega'    => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (OrdenCompra $orden): void {
            if (empty($orden->codigo)) {
                $year = now()->year;
                $ultimoCodigo = static::query()
                    ->where('codigo', 'like', "OC-{$year}-%")
                    ->orderByDesc('codigo')
                    ->value('codigo');

                $ultimoNumero = $ultimoCodigo && preg_match('/^OC-\d{4}-(\d+)$/', $ultimoCodigo, $m)
                    ? (int) $m[1]
                    : 0;

                $orden->codigo = sprintf('OC-%d-%04d', $year, $ultimoNumero + 1);
            }
        });
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
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

    public function estaCompletamenteRecibida(): bool
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();

        if ($items->isEmpty()) {
            return false;
        }

        return $items->every(fn (OrdenCompraItem $item): bool =>
            $item->cantidad_recibida >= $item->cantidad_solicitada
        );
    }

    public function tieneRecepcionParcial(): bool
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();

        return $items->contains(fn (OrdenCompraItem $item): bool =>
            $item->cantidad_recibida > 0 && $item->cantidad_recibida < $item->cantidad_solicitada
        ) || (
            $items->contains(fn (OrdenCompraItem $item): bool => $item->cantidad_recibida > 0)
            && ! $this->estaCompletamenteRecibida()
        );
    }
}
