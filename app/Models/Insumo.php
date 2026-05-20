<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Insumo extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'insumos';

    protected $fillable = [
        'codigo',
        'nombre',
        'unidad_medida_id',
        'stock_minimo',
        'stock_actual',
        'precio_costo',
        'ubicacion',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'stock_minimo' => 'float',
        'stock_actual' => 'float',
        'precio_costo' => 'float',
        'activo'       => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Insumo $insumo): void {
            if (empty($insumo->codigo)) {
                $next = (static::withTrashed()->max('id') ?? 0) + 1;
                $insumo->codigo = 'INS-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    public function composiciones(): HasMany
    {
        return $this->hasMany(ComposicionTecnica::class, 'insumo_id');
    }

    public function reservasActivas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReservaStock::class)->where('estado', 'activa');
    }

    public function getStockReservadoAttribute(): float
    {
        return (float) $this->reservasActivas()->sum('cantidad_reservada');
    }

    public function getStockDisponibleAttribute(): float
    {
        return max(0, ($this->stock_actual ?? 0) - $this->stock_reservado);
    }

    public function getEsCriticoAttribute(): bool
    {
        return $this->stock_disponible <= ($this->stock_minimo ?? 0);
    }
}
