<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Reclamo extends Model
{
    use LogsActivity;

    protected $fillable = [
        'agencia_id',
        'presupuesto_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function agencia(): BelongsTo
    {
        return $this->belongsTo(Agencia::class, 'agencia_id');
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class, 'presupuesto_id');
    }

    public function registros(): HasMany
    {
        return $this->hasMany(ReclamoRegistro::class)->latest('fecha');
    }

    public function puedeEditarCabecera(): bool
    {
        return ! $this->registros()->exists();
    }

    /**
     * @return Collection<int, Mobiliario>
     */
    public function mobiliariosDelPresupuesto(): Collection
    {
        $this->loadMissing('presupuesto.items.mobiliario');

        return ($this->presupuesto?->items ?? collect())
            ->pluck('mobiliario')
            ->filter()
            ->unique('id')
            ->sortBy('nombre')
            ->values();
    }

    /**
     * @return array<int, string>
     */
    public static function opcionesMobiliarioParaPresupuesto(?int $presupuestoId): array
    {
        if (! $presupuestoId) {
            return [];
        }

        $presupuesto = Presupuesto::query()
            ->with('items.mobiliario')
            ->find($presupuestoId);

        return ($presupuesto?->items ?? collect())
            ->pluck('mobiliario')
            ->filter()
            ->unique('id')
            ->sortBy('nombre')
            ->mapWithKeys(fn (Mobiliario $mobiliario): array => [
                $mobiliario->id => trim(($mobiliario->codigo_interno ? "{$mobiliario->codigo_interno} — " : '').$mobiliario->nombre),
            ])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public function opcionesMobiliario(): array
    {
        return static::opcionesMobiliarioParaPresupuesto($this->presupuesto_id);
    }
}
