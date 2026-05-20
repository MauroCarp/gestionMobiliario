<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Str;

class CategoriaMobiliario extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'categorias_mobiliario';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'icono',
        'activo',
        'orden',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden'  => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected static function booted(): void
    {
        static::creating(function (self $categoria) {
            if (empty($categoria->slug)) {
                $categoria->slug = Str::slug($categoria->nombre);
            }
        });
    }

    public function mobiliarios(): HasMany
    {
        return $this->hasMany(Mobiliario::class, 'categoria_id');
    }
}
