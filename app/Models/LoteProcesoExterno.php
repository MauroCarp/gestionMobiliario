<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoteProcesoExterno extends Model
{
    use SoftDeletes;

    protected $table = 'lotes_proceso_externo';

    const ESTADOS = [
        'pendiente'  => 'Pendiente',
        'en_proceso' => 'En proceso',
        'completado' => 'Completado',
        'cancelado'  => 'Cancelado',
    ];

    const ESTADO_COLORS = [
        'pendiente'  => 'gray',
        'en_proceso' => 'warning',
        'completado' => 'success',
        'cancelado'  => 'danger',
    ];

    const ORIGENES = [
        'orden_compra' => 'Orden de Compra',
        'proyecto'     => 'Proyecto',
        'manual'       => 'Manual',
    ];

    protected $fillable = [
        'codigo',
        'plantilla_id',
        'entidad_tipo',
        'entidad_id',
        'cantidad',
        'origen_tipo',
        'origen_id',
        'estado',
        'fecha_inicio',
        'fecha_finalizacion_estimada',
        'fecha_finalizacion_real',
        'observaciones',
    ];

    protected $casts = [
        'cantidad'                    => 'float',
        'fecha_inicio'                => 'date',
        'fecha_finalizacion_estimada' => 'date',
        'fecha_finalizacion_real'     => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $lote): void {
            if (empty($lote->codigo)) {
                $year  = now()->year;
                $count = static::withTrashed()->whereYear('created_at', $year)->count() + 1;
                $lote->codigo = sprintf('LPE-%d-%04d', $year, $count);
            }
        });
    }

    public function plantilla(): BelongsTo
    {
        return $this->belongsTo(PlantillaFlujoExterno::class, 'plantilla_id');
    }

    public function etapas(): HasMany
    {
        return $this->hasMany(LoteEtapa::class, 'lote_id')->orderBy('orden')->orderBy('id');
    }

    public function getEntidadNombreAttribute(): string
    {
        return match ($this->entidad_tipo) {
            'insumo'     => Insumo::find($this->entidad_id)?->nombre ?? '—',
            'mobiliario' => Mobiliario::find($this->entidad_id)?->nombre ?? '—',
            default      => '—',
        };
    }

    public function getEntidadCodigoAttribute(): ?string
    {
        return match ($this->entidad_tipo) {
            'insumo'     => Insumo::find($this->entidad_id)?->codigo,
            'mobiliario' => Mobiliario::find($this->entidad_id)?->codigo_interno,
            default      => null,
        };
    }

    public function getEtapaActualAttribute(): ?LoteEtapa
    {
        return $this->etapas->firstWhere('estado', '!=', 'completado');
    }

    /**
     * Crea las etapas del lote a partir de una plantilla.
     */
    public function crearEtapasDesde(PlantillaFlujoExterno $plantilla): void
    {
        foreach ($plantilla->etapas as $pe) {
            $this->etapas()->create([
                'orden'                    => $pe->orden,
                'tipo_proceso_id'          => $pe->tipo_proceso_id,
                'tercero_id'               => $pe->tercero_id,
                'estado'                   => 'pendiente',
                'fecha_recepcion_estimada' => $pe->dias_estimados
                    ? now()->addDays($pe->dias_estimados)->toDateString()
                    : null,
            ]);
        }
    }
}
