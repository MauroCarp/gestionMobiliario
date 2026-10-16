<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * @return array<string, string>
     */
    public static function opcionesItemParaPresupuesto(?int $presupuestoId): array
    {
        if (! $presupuestoId) {
            return [];
        }

        $presupuesto = Presupuesto::query()
            ->with([
                'agencia.proyecto',
                'items.mobiliario',
                'items.insumo.categoriasInsumo',
                'items.insumo.marcasSilla',
            ])
            ->find($presupuestoId);

        if (! $presupuesto) {
            return [];
        }

        $marcaId = $presupuesto->agencia?->proyecto?->marca_id;
        $opciones = [];

        foreach ($presupuesto->items as $item) {
            if ($item->mobiliario) {
                $mobiliario = $item->mobiliario;
                $opciones['mob_'.$mobiliario->id] = trim(
                    ($mobiliario->codigo_interno ? "{$mobiliario->codigo_interno} — " : '').$mobiliario->nombre
                );
            }

            if ($item->insumo?->esSilla()) {
                $insumo = $item->insumo;
                $fantasia = $insumo->nombreParaMarca($marcaId);
                $real = $insumo->nombre;
                $opciones['ins_'.$insumo->id] = filled($fantasia) && $fantasia !== $real
                    ? "{$fantasia} — {$real}"
                    : $real;
            }
        }

        asort($opciones, SORT_NATURAL | SORT_FLAG_CASE);

        return $opciones;
    }

    /**
     * @return array<string, string>
     */
    public function opcionesItem(): array
    {
        return static::opcionesItemParaPresupuesto($this->presupuesto_id);
    }
}
