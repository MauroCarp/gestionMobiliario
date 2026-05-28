<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proveedor extends Model
{
    use SoftDeletes;

    protected $table = 'proveedores';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->codigo)) {
                $last = static::withTrashed()
                    ->where('codigo', 'like', 'PRV-%')
                    ->orderByRaw('CAST(SUBSTRING(codigo, 5) AS UNSIGNED) DESC')
                    ->value('codigo');

                $next = $last ? ((int) substr($last, 4)) + 1 : 1;
                $model->codigo = 'PRV-' . str_pad($next, 2, '0', STR_PAD_LEFT);
            }
        });
    }

    const CONDICIONES_IVA = [
        'responsable_inscripto' => 'Responsable Inscripto',
        'monotributista'        => 'Monotributista',
        'exento'                => 'Exento',
        'no_responsable'        => 'No Responsable',
        'consumidor_final'      => 'Consumidor Final',
    ];

    protected $fillable = [
        'codigo',
        'razon_social',
        'nombre_comercial',
        'cuit',
        'condicion_iva',
        'direccion',
        'provincia_id',
        'ciudad_id',
        'telefono',
        'email',
        'persona_contacto',
        'activo',
        'observaciones',
        'rubro_id',
        'condicion_pago',
        'lista_precio',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function rubro(): BelongsTo
    {
        return $this->belongsTo(Rubro::class);
    }
}
