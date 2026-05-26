<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    protected $fillable = ['nombre', 'provincia_id', 'codigo_postal'];
    public $timestamps = false;

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    public function agencias(): HasMany
    {
        return $this->hasMany(Agencia::class);
    }
}
