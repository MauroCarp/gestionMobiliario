<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProyectoHistorial extends Model
{
    protected $table = 'proyecto_historial';

    protected $fillable = [
        'proyecto_id',
        'estado_anterior',
        'estado_nuevo',
        'observacion',
        'user_id',
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getEstadoAnteriorLabelAttribute(): string
    {
        return Proyecto::ESTADOS[$this->estado_anterior] ?? $this->estado_anterior ?? '—';
    }

    public function getEstadoNuevoLabelAttribute(): string
    {
        return Proyecto::ESTADOS[$this->estado_nuevo] ?? $this->estado_nuevo;
    }
}
