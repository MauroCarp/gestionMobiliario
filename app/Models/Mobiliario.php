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
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Mobiliario extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $table = 'mobiliarios';

    const ESTADOS = [
        'activo'       => 'Activo',
        'inactivo'     => 'Inactivo',
        'discontinuado' => 'Discontinuado',
    ];

    protected $fillable = [
        'codigo_interno',
        'nombre',
        'categoria_id',
        'imagen',
        'descripcion',
        'observaciones',
        'estado',
        'version_actual',
    ];

    protected $casts = [
        'version_actual' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('imagenes')
            ->useDisk('public')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('documentos')
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)->height(300)->sharpen(10);

        $this->addMediaConversion('medium')
            ->width(800)->height(600);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaMobiliario::class, 'categoria_id');
    }

    public function atributos(): HasMany
    {
        return $this->hasMany(AtributoMobiliario::class, 'mobiliario_id');
    }

    public function composicionTecnica(): HasMany
    {
        return $this->hasMany(ComposicionTecnica::class, 'mobiliario_id')
            ->where('version', $this->version_actual)
            ->where('activo', true);
    }

    public function todasVersionesComposicion(): HasMany
    {
        return $this->hasMany(ComposicionTecnica::class, 'mobiliario_id');
    }

    public function historialVersiones(): HasMany
    {
        return $this->hasMany(HistorialVersion::class, 'mobiliario_id')->orderByDesc('version');
    }

    public function proyectos(): BelongsToMany
    {
        return $this->belongsToMany(Proyecto::class, 'proyecto_mobiliario')
            ->withPivot('cantidad', 'observaciones')
            ->withTimestamps();
    }
}
