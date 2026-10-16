<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ReclamoRegistro extends Model implements HasMedia
{
    use LogsActivity, InteractsWithMedia;

    protected $table = 'reclamo_registros';

    public const ESTADOS = [
        'pendiente' => 'Pendiente',
        'resuelto' => 'Resuelto',
    ];

    public const ESTADO_COLORS = [
        'pendiente' => 'warning',
        'resuelto' => 'success',
    ];

    protected $fillable = [
        'reclamo_id',
        'mobiliario_id',
        'insumo_id',
        'observacion',
        'fecha',
        'estado',
        'costo',
        'costo_logistica',
        'fecha_resuelto',
    ];

    protected $attributes = [
        'estado' => 'pendiente',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_resuelto' => 'date',
        'costo' => 'decimal:2',
        'costo_logistica' => 'decimal:2',
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
        $this->addMediaCollection('imagenes')
            ->useDisk('public')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
                'video/mp4',
                'video/webm',
            ]);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($media && ! str_starts_with((string) $media->mime_type, 'image/')) {
            return;
        }

        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->sharpen(10);
    }

    public function reclamo(): BelongsTo
    {
        return $this->belongsTo(Reclamo::class, 'reclamo_id');
    }

    public function mobiliario(): BelongsTo
    {
        return $this->belongsTo(Mobiliario::class, 'mobiliario_id');
    }

    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    public function nombreItem(?int $marcaId = null): string
    {
        if ($this->mobiliario) {
            return trim(($this->mobiliario->codigo_interno ? "{$this->mobiliario->codigo_interno} — " : '').$this->mobiliario->nombre);
        }

        if ($this->insumo) {
            $this->loadMissing(['insumo.marcasSilla', 'reclamo.agencia.proyecto']);
            $marcaId ??= $this->reclamo?->agencia?->proyecto?->marca_id;
            $fantasia = $this->insumo->nombreParaMarca($marcaId);
            $real = $this->insumo->nombre;

            return filled($fantasia) && $fantasia !== $real
                ? "{$fantasia} — {$real}"
                : $real;
        }

        return '—';
    }

    public function etiquetaListado(?int $marcaId = null): string
    {
        $item = $this->nombreItem($marcaId);
        $motivo = trim((string) $this->observacion);

        return $motivo === '' ? $item : "{$item} — {$motivo}";
    }

    public function estaPendiente(): bool
    {
        return $this->estado === 'pendiente';
    }

    public function estaResuelto(): bool
    {
        return $this->estado === 'resuelto';
    }
}
