<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantillaEtapa extends Model
{
    protected $table = 'plantilla_etapas';

    protected $fillable = [
        'plantilla_id',
        'orden',
        'tipo_proceso_id',
        'tercero_id',
        'dias_estimados',
        'ultimo_precio',
        'fecha_ultimo_precio',
        'observaciones',
    ];

    protected $casts = [
        'orden'                => 'integer',
        'dias_estimados'       => 'integer',
        'ultimo_precio'        => 'float',
        'fecha_ultimo_precio'  => 'date',
    ];

    public function plantilla(): BelongsTo
    {
        return $this->belongsTo(PlantillaFlujoExterno::class, 'plantilla_id');
    }

    public function tipoProceso(): BelongsTo
    {
        return $this->belongsTo(TipoProcesoExterno::class, 'tipo_proceso_id');
    }

    public function tercero(): BelongsTo
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    }
}
