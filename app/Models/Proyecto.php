<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Proyecto extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'proyectos';

    const ESTADOS = [
        'presupuestar'   => 'Presupuestar',
        'presupuestado'  => 'Presupuestado',
        'confirmado'     => 'Pagado / Confirmado',
        'en_produccion'  => 'En producción',
        'entregado'      => 'Entregado',
    ];

    protected $fillable = [
        'codigo_interno',
        'marca_id',
        'manual_pdf',
        'estado',
        'fecha_inicio',
        'fecha_entrega_estimada',
        'fecha_entrega_real',
        'observaciones',
        'timeline',
    ];

    protected $casts = [
        'fecha_inicio'             => 'date',
        'fecha_entrega_estimada'   => 'date',
        'fecha_entrega_real'       => 'date',
        'timeline'                 => 'array',
        'manual_pdf'               => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected static function booted(): void
    {
        static::updating(function (self $proyecto) {
            if ($proyecto->isDirty('estado')) {
                ProyectoHistorial::create([
                    'proyecto_id'    => $proyecto->id,
                    'estado_anterior' => $proyecto->getOriginal('estado'),
                    'estado_nuevo'   => $proyecto->estado,
                    'user_id'        => auth()->check() ? auth()->id() : null,
                ]);
            }
        });
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function agencias(): HasMany
    {
        return $this->hasMany(Agencia::class, 'proyecto_id');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(ProyectoHistorial::class, 'proyecto_id')->latest();
    }

    public function mobiliarios(): BelongsToMany
    {
        return $this->belongsToMany(Mobiliario::class, 'proyecto_mobiliario')
            ->withPivot('cantidad', 'observaciones')
            ->withTimestamps();
    }

    public function mobiliariosPivot(): HasMany
    {
        return $this->hasMany(ProyectoMobiliario::class, 'proyecto_id');
    }

    public function getEstadoLabelAttribute(): string
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }
}
