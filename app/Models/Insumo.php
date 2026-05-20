<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Insumo extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'insumos';

    protected $fillable = [
        'codigo',
        'nombre',
        'unidad_medida_id',
        'stock_minimo',
        'ubicacion',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'stock_minimo' => 'float',
        'activo'       => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    public function composiciones(): HasMany
    {
        return $this->hasMany(ComposicionTecnica::class, 'insumo_id');
    }
}
