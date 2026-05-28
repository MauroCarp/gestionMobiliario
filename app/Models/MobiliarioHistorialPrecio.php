<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobiliarioHistorialPrecio extends Model
{
    protected $table = 'mobiliario_historial_precios';

    public $timestamps = false;

    protected $fillable = [
        'mobiliario_id',
        'user_id',
        'precio',
        'created_at',
    ];

    protected $casts = [
        'precio'     => 'float',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            $model->created_at ??= now();
        });
    }

    public function mobiliario(): BelongsTo
    {
        return $this->belongsTo(Mobiliario::class, 'mobiliario_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
