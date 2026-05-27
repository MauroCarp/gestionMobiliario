<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Marca;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
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
        'marca_id',
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
        // Fit::Contain redimensiona respetando el aspecto sin recortar.
        // Si la imagen no es cuadrada aparecerán márgenes blancos en lugar de cortar.
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->sharpen(10);

        // Fit::Max escala hacia abajo para caber en 800×600 sin recortar ni agrandar.
        $this->addMediaConversion('medium')
            ->fit(Fit::Max, 800, 600);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaMobiliario::class, 'categoria_id');
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function plantillaFlujos(): HasMany
    {
        return $this->hasMany(PlantillaFlujoExterno::class, 'entidad_id')
            ->where('entidad_tipo', 'mobiliario');
    }

    public function lotesProcesoExterno(): HasMany
    {
        return $this->hasMany(LoteProcesoExterno::class, 'entidad_id')
            ->where('entidad_tipo', 'mobiliario');
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
