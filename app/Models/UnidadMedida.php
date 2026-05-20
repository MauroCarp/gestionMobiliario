<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UnidadMedida extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'unidades_medida';

    protected $fillable = [
        'nombre',
        'abreviatura',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function insumos(): HasMany
    {
        return $this->hasMany(Insumo::class, 'unidad_medida_id');
    }
}
