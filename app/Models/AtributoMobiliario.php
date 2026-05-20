<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtributoMobiliario extends Model
{
    protected $table = 'atributos_mobiliario';

    protected $fillable = [
        'mobiliario_id',
        'clave',
        'valor',
    ];

    public function mobiliario(): BelongsTo
    {
        return $this->belongsTo(Mobiliario::class, 'mobiliario_id');
    }
}
