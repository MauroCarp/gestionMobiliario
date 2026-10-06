<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Marca;
use App\Models\MobiliarioHistorialPrecio;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Mobiliario extends Model implements HasMedia
{
    use HasFactory, LogsActivity, InteractsWithMedia;

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
        'precio',
        'stock_actual',
        'version_actual',
    ];

    protected $attributes = [
        'stock_actual' => 0,
    ];

    protected $casts = [
        'version_actual' => 'integer',
        'stock_actual'   => 'integer',
        'precio'         => 'float',
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

        $this->addMediaCollection('galeria')
            ->useDisk('public')
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

    public function marcas(): BelongsToMany
    {
        return $this->belongsToMany(Marca::class, 'marca_mobiliario');
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

    public function historialPrecios(): HasMany
    {
        return $this->hasMany(MobiliarioHistorialPrecio::class, 'mobiliario_id')->latest('created_at');
    }

    public function composicionTecnica(): HasMany
    {
        $composicionTable = (new ComposicionTecnica)->getTable();
        $mobiliarioTable  = $this->getTable();

        return $this->hasMany(ComposicionTecnica::class, 'mobiliario_id')
            ->where("{$composicionTable}.activo", true)
            ->whereRaw(
                "{$composicionTable}.version = (SELECT version_actual FROM {$mobiliarioTable} WHERE {$mobiliarioTable}.id = {$composicionTable}.mobiliario_id)",
            );
    }

    public function composicionFabricable()
    {
        $this->loadMissing('composicionTecnica.insumo');

        return $this->composicionTecnica
            ->filter(fn (ComposicionTecnica $comp): bool => ! $comp->es_componente_casco && (float) $comp->cantidad > 0)
            ->values();
    }

    /**
     * @return array{
     *     insumos: list<array{codigo: ?string, nombre: ?string, cantidad: float, unidad: ?string, precio_costo: float, subtotal: float, es_casco: bool}>,
     *     etapas: list<array{plantilla: ?string, orden: ?int, tipo_proceso: ?string, tercero: ?string, ultimo_precio: float}>,
     *     plantillas_inactivas: list<string>,
     *     subtotal_insumos: float,
     *     subtotal_etapas: float,
     *     total: float
     * }
     */
    public function desgloseCostoDirecto(): array
    {
        $this->loadMissing([
            'composicionTecnica.insumo.unidadMedida',
            'plantillaFlujos.etapas.tipoProceso',
            'plantillaFlujos.etapas.tercero',
        ]);

        $insumos = [];
        $sumaInsumos = 0.0;

        foreach ($this->composicionTecnica as $comp) {
            $cantidad = (float) $comp->cantidad;
            $precioCosto = (float) ($comp->insumo?->precio_costo ?? 0);
            $subtotal = $cantidad * $precioCosto;
            $sumaInsumos += $subtotal;

            $insumos[] = [
                'codigo' => $comp->insumo?->codigo,
                'nombre' => $comp->insumo?->nombre,
                'cantidad' => $cantidad,
                'unidad' => $comp->insumo?->unidadMedida?->abreviatura
                    ?? $comp->insumo?->unidadMedida?->nombre,
                'precio_costo' => $precioCosto,
                'subtotal' => round($subtotal, 2),
                'es_casco' => (bool) $comp->es_componente_casco,
            ];
        }

        $etapas = [];
        $plantillasInactivas = [];
        $sumaEtapas = 0.0;

        foreach ($this->plantillaFlujos as $plantilla) {
            if (! $plantilla->activo) {
                $plantillasInactivas[] = $plantilla->nombre ?: '—';

                continue;
            }

            foreach ($plantilla->etapas as $etapa) {
                $precio = (float) ($etapa->ultimo_precio ?? 0);
                $sumaEtapas += $precio;

                $etapas[] = [
                    'plantilla' => $plantilla->nombre,
                    'orden' => $etapa->orden,
                    'tipo_proceso' => $etapa->tipoProceso?->nombre,
                    'tercero' => $etapa->tercero?->nombre,
                    'ultimo_precio' => $precio,
                ];
            }
        }

        return [
            'insumos' => $insumos,
            'etapas' => $etapas,
            'plantillas_inactivas' => $plantillasInactivas,
            'subtotal_insumos' => round($sumaInsumos, 2),
            'subtotal_etapas' => round($sumaEtapas, 2),
            'total' => round($sumaInsumos + $sumaEtapas, 2),
        ];
    }

    public function costoDirecto(): float
    {
        return $this->desgloseCostoDirecto()['total'];
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
            ->withPivot('cantidad', 'observaciones', 'sector_id')
            ->withTimestamps();
    }

    public function asignarAProyectosDeMarcas(): void
    {
        $marcaIds = $this->marcas()->pluck('marcas.id');

        if ($marcaIds->isEmpty()) {
            return;
        }

        $proyectoIds = Proyecto::query()
            ->whereIn('marca_id', $marcaIds)
            ->pluck('id');

        if ($proyectoIds->isEmpty()) {
            return;
        }

        $this->proyectos()->syncWithoutDetaching(
            $proyectoIds->mapWithKeys(fn ($id): array => [
                (int) $id => [
                    'cantidad' => 1,
                    'observaciones' => null,
                    'sector_id' => null,
                ],
            ])->all()
        );
    }

    public function presupuestoItems(): HasMany
    {
        return $this->hasMany(PresupuestoItem::class, 'mobiliario_id');
    }

    public function ordenProduccionItems(): HasMany
    {
        return $this->hasMany(OrdenProduccionItem::class, 'mobiliario_id');
    }

    public function presupuestoItemsPendientesEntrega(): HasMany
    {
        return $this->presupuestoItems()
            ->pendienteEntrega()
            ->whereHas('presupuesto', fn ($query) => $query->whereIn('estado', [
                'confirmado',
                'pagado',
                'entregado_parcial',
            ]));
    }
}
