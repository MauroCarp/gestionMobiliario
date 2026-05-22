<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Marca extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'marcas';

    protected $fillable = [
        'nombre',
        'logo',
        'manual_pdf',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class, 'marca_id');
    }

    /** Agencias alcanzables a través de los proyectos de esta marca. */
    public function agencias(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Agencia::class, Proyecto::class, 'marca_id', 'proyecto_id');
    }
}
