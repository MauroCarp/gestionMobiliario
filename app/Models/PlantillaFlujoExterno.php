<?php

namespace App\Models;

use App\Services\StockCascoService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlantillaFlujoExterno extends Model
{
    protected $table = 'plantillas_flujo_externo';

    const ENTIDAD_TIPOS = [
        'insumo' => 'Insumo',
        'mobiliario' => 'Mobiliario',
    ];

    protected $fillable = [
        'nombre',
        'entidad_tipo',
        'entidad_id',
        'activo',
        'stock_casco',
    ];

    protected $attributes = [
        'stock_casco' => 0,
    ];

    protected $casts = [
        'activo' => 'boolean',
        'stock_casco' => 'integer',
    ];

    public function mobiliario(): BelongsTo
    {
        return $this->belongsTo(Mobiliario::class, 'entidad_id');
    }

    public function scopeCascosSilla(Builder $query): Builder
    {
        return $query
            ->where('entidad_tipo', 'mobiliario')
            ->whereHas('mobiliario.categoria', fn (Builder $q) => $q->where('nombre', 'like', '%silla%'));
    }

    public function esCascoSilla(): bool
    {
        if ($this->entidad_tipo !== 'mobiliario') {
            return false;
        }

        $this->loadMissing('mobiliario.categoria');

        return str_contains(
            mb_strtolower($this->mobiliario?->categoria?->nombre ?? ''),
            'silla',
        );
    }

    public function etapas(): HasMany
    {
        return $this->hasMany(PlantillaEtapa::class, 'plantilla_id')->orderBy('orden');
    }

    public function lotes(): HasMany
    {
        return $this->hasMany(LoteProcesoExterno::class, 'plantilla_id');
    }

    public function getEntidadNombreAttribute(): string
    {
        return match ($this->entidad_tipo) {
            'insumo' => Insumo::find($this->entidad_id)?->nombre ?? '—',
            'mobiliario' => Mobiliario::find($this->entidad_id)?->nombre ?? '—',
            default => '—',
        };
    }

    public function getCascoComprometidoAttribute(): int
    {
        if ($this->entidad_tipo !== 'mobiliario' || ! $this->entidad_id) {
            return 0;
        }

        return app(StockCascoService::class)->demandaActiva((int) $this->entidad_id);
    }

    public function getCascoEnFabricacionAttribute(): int
    {
        if ($this->entidad_tipo !== 'mobiliario' || ! $this->entidad_id) {
            return 0;
        }

        return app(StockCascoService::class)->cantidadEnLotesAbiertos(
            (int) $this->entidad_id,
            (int) $this->id,
        );
    }

    public function getStockProyectadoCascoAttribute(): int
    {
        return (int) $this->stock_casco
            + $this->casco_en_fabricacion
            - $this->casco_comprometido;
    }
}
