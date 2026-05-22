<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Agencia extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $table = 'agencias';

    protected $fillable = [
        'nombre',
        'codigo_interno',
        'marca_id',
        'direccion',
        'responsable',
        'observaciones',
        'activo',
        'prioridad',
        'etiquetas',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'etiquetas' => 'array',
        'prioridad' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('archivos')
            ->useDisk('public');

        $this->addMediaCollection('planos')
            ->useDisk('public')
            ->acceptsMimeTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/svg+xml']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->sharpen(10);
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class, 'agencia_id');
    }

    public function getPrioridadLabelAttribute(): string
    {
        return match ($this->prioridad) {
            1 => 'Alta',
            2 => 'Media',
            default => 'Baja',
        };
    }
}
