<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoteEtapa extends Model
{
    protected $table = 'lote_etapas';

    const ESTADOS = [
        'pendiente'   => 'Pendiente',
        'en_transito' => 'En tránsito',
        'en_proceso'  => 'En proceso',
        'completado'  => 'Completado',
    ];

    const ESTADO_COLORS = [
        'pendiente'   => 'gray',
        'en_transito' => 'info',
        'en_proceso'  => 'warning',
        'completado'  => 'success',
    ];

    const ESTADO_ICONS = [
        'pendiente'   => 'heroicon-o-clock',
        'en_transito' => 'heroicon-o-truck',
        'en_proceso'  => 'heroicon-o-wrench-screwdriver',
        'completado'  => 'heroicon-o-check-circle',
    ];

    protected $fillable = [
        'lote_id',
        'orden',
        'tipo_proceso_id',
        'tercero_id',
        'estado',
        'fecha_envio',
        'fecha_recepcion_estimada',
        'fecha_recepcion_real',
        'costo',
        'observaciones',
        'usuario_id',
    ];

    protected $casts = [
        'orden'                    => 'integer',
        'costo'                    => 'float',
        'fecha_envio'              => 'date',
        'fecha_recepcion_estimada' => 'date',
        'fecha_recepcion_real'     => 'date',
    ];

    public function lote(): BelongsTo
    {
        return $this->belongsTo(LoteProcesoExterno::class, 'lote_id');
    }

    public function tipoProceso(): BelongsTo
    {
        return $this->belongsTo(TipoProcesoExterno::class, 'tipo_proceso_id');
    }

    public function tercero(): BelongsTo
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Verifica que todas las etapas del orden anterior estén completadas.
     */
    public function puedeIniciar(): bool
    {
        if ($this->estado !== 'pendiente') {
            return false;
        }

        if ($this->orden <= 1) {
            return true;
        }

        return ! $this->lote->etapas()
            ->where('orden', '<', $this->orden)
            ->where('estado', '!=', 'completado')
            ->exists();
    }
}
