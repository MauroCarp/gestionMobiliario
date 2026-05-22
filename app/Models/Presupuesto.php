<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Presupuesto extends Model
{
    use SoftDeletes, LogsActivity;

    const ESTADOS = [
        'borrador'    => 'Borrador',
        'en_revision' => 'En Revisión',
        'aprobado'    => 'Aprobado',
        'confirmado'  => 'Confirmado',
        'pagado'      => 'Pagado',
        'rechazado'   => 'Rechazado',
        'cancelado'   => 'Cancelado',
    ];

    const ESTADO_COLORS = [
        'borrador'    => 'gray',
        'en_revision' => 'warning',
        'aprobado'    => 'success',
        'confirmado'  => 'info',
        'pagado'      => 'success',
        'rechazado'   => 'danger',
        'cancelado'   => 'gray',
    ];

    protected $fillable = [
        'codigo',
        'agencia_id',
        'responsable_id',
        'estado',
        'version',
        'fecha_emision',
        'fecha_vencimiento',
        'observaciones',
        'notas_internas',
        'aprobado_por',
        'aprobado_at',
        'datos_adicionales',
    ];

    protected $casts = [
        'fecha_emision'     => 'date',
        'fecha_vencimiento' => 'date',
        'aprobado_at'       => 'datetime',
        'datos_adicionales' => 'array',
        'version'           => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    protected static function booted(): void
    {
        static::creating(function (self $presupuesto): void {
            if (empty($presupuesto->codigo)) {
                $year  = now()->year;
                $count = static::whereYear('created_at', $year)->withTrashed()->count() + 1;
                $presupuesto->codigo = sprintf('PRES-%d-%04d', $year, $count);
            }
            if (empty($presupuesto->fecha_emision)) {
                $presupuesto->fecha_emision = now()->toDateString();
            }
        });
    }

    // ─── State machine ────────────────────────────────────────────────────────

    public function cambiarEstado(string $nuevoEstado, ?string $comentario = null): void
    {
        $estadoAnterior = $this->estado;
        $this->update(['estado' => $nuevoEstado]);

        PresupuestoHistorial::create([
            'presupuesto_id'  => $this->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => $nuevoEstado,
            'comentario'      => $comentario,
            'user_id'         => auth()->check() ? auth()->id() : null,
        ]);

        if ($nuevoEstado === 'aprobado') {
            $this->update([
                'aprobado_por' => auth()->check() ? auth()->id() : null,
                'aprobado_at'  => now(),
            ]);
        }
    }

    public function crearVersion(?string $motivo = null): PresupuestoVersion
    {
        return $this->versiones()->create([
            'numero_version' => $this->version,
            'snapshot'       => [
                'presupuesto' => $this->toArray(),
                'items'       => $this->items->load('mobiliario')->toArray(),
            ],
            'motivo_cambio'  => $motivo,
            'creado_por'     => auth()->check() ? auth()->id() : null,
        ]);
    }

    // ─── State helpers ────────────────────────────────────────────────────────

    public function puedeEnviarARevision(): bool
    {
        return $this->estado === 'borrador';
    }

    public function puedeAprobar(): bool
    {
        return $this->estado === 'en_revision';
    }

    public function puedeRechazar(): bool
    {
        return $this->estado === 'en_revision';
    }

    public function puedeCancelar(): bool
    {
        return in_array($this->estado, ['borrador', 'en_revision', 'aprobado', 'confirmado']);
    }

    public function puedeEditar(): bool
    {
        return in_array($this->estado, ['borrador', 'rechazado']);
    }

    // ─── Relations ────────────────────────────────────────────────────────────

    /** El proyecto se obtiene a través de la agencia asignada. */
    public function getProyectoAttribute(): ?Proyecto
    {
        return $this->agencia?->proyecto;
    }

    public function agencia(): BelongsTo
    {
        return $this->belongsTo(Agencia::class, 'agencia_id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function aprobadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PresupuestoItem::class)->orderBy('orden');
    }

    public function versiones(): HasMany
    {
        return $this->hasMany(PresupuestoVersion::class)->orderByDesc('numero_version');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(PresupuestoHistorial::class)->latest();
    }

    public function reservasStock(): HasMany
    {
        return $this->hasMany(ReservaStock::class);
    }

    public function ordenesCompra(): HasMany
    {
        return $this->hasMany(OrdenCompra::class);
    }

    public function esConfirmado(): bool
    {
        return in_array($this->estado, ['confirmado', 'pagado']);
    }
}
