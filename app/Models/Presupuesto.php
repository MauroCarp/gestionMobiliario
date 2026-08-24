<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Presupuesto extends Model
{
    use LogsActivity;

    const ESTADOS = [
        'borrador'          => 'Borrador',
        'en_revision'       => 'En Revisión',
        'aprobado'          => 'Aprobado',
        'confirmado'        => 'Confirmado',
        'pagado'            => 'Pagado',
        'entregado_parcial' => 'Entregado parcial',
        'entregado'         => 'Entregado',
        'rechazado'         => 'Rechazado',
        'cancelado'         => 'Cancelado',
    ];

    const ESTADO_COLORS = [
        'borrador'          => 'gray',
        'en_revision'       => 'warning',
        'aprobado'          => 'success',
        'confirmado'        => 'info',
        'pagado'            => 'success',
        'entregado_parcial' => 'warning',
        'entregado'         => 'success',
        'rechazado'         => 'danger',
        'cancelado'         => 'gray',
    ];

    const TEXTO_PAGO_DEFECTO = '50% MEDIANTE TRANSFERENCIA AL CONFIRMAR EL PRESUPUESTO, SALDO CON E-CHEQ ANTICIPADOS AL CONFIRMAR 15-30-45-60-75-90 DÍAS F.F..';

    const LEYENDA_LOGISTICA_PROPIA = 'Logística e instalación propia';

    protected $fillable = [
        'codigo',
        'agencia_id',
        'responsable_id',
        'estado',
        'version',
        'fecha_emision',
        'fecha_vencimiento',
        'metodo_pago',
        'logistica_instalacion_propia',
        'logistica_leyenda',
        'logistica_costo',
        'observaciones',
        'notas_internas',
        'aprobado_por',
        'aprobado_at',
        'datos_adicionales',
    ];

    protected $casts = [
        'fecha_emision'     => 'date',
        'fecha_vencimiento' => 'date',
        'aprobado_at'       => 'datetime',
        'datos_adicionales' => 'array',
        'version'           => 'integer',
        'logistica_instalacion_propia' => 'boolean',
        'logistica_costo'   => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    protected static function booted(): void
    {
        static::creating(function (self $presupuesto): void {
            if (empty($presupuesto->codigo)) {
                $year = now()->year;
                $ultimoCodigo = static::query()
                    ->where('codigo', 'like', "PRES-{$year}-%")
                    ->orderByDesc('codigo')
                    ->value('codigo');

                $ultimoNumero = $ultimoCodigo && preg_match('/^PRES-\d{4}-(\d+)$/', $ultimoCodigo, $matches)
                    ? (int) $matches[1]
                    : 0;

                $presupuesto->codigo = sprintf('PRES-%d-%04d', $year, $ultimoNumero + 1);
            }
            if (empty($presupuesto->fecha_emision)) {
                $presupuesto->fecha_emision = now()->toDateString();
            }
            if (empty($presupuesto->metodo_pago)) {
                $presupuesto->metodo_pago = static::textoBasesCondicionesDefault();
            }
        });
    }

    // ─── State machine ────────────────────────────────────────────────────────

    public function cambiarEstado(string $nuevoEstado, ?string $comentario = null): void
    {
        $estadoAnterior = $this->estado;
        $this->update(['estado' => $nuevoEstado]);

        PresupuestoHistorial::create([
            'presupuesto_id'  => $this->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => $nuevoEstado,
            'comentario'      => $comentario,
            'user_id'         => auth()->check() ? auth()->id() : null,
        ]);

        if ($nuevoEstado === 'aprobado') {
            $this->update([
                'aprobado_por' => auth()->check() ? auth()->id() : null,
                'aprobado_at'  => now(),
            ]);
        }
    }

    public function crearVersion(?string $motivo = null): PresupuestoVersion
    {
        return $this->versiones()->create([
            'numero_version' => $this->version,
            'snapshot'       => [
                'presupuesto' => $this->toArray(),
                'items'       => $this->items->load('mobiliario')->toArray(),
            ],
            'motivo_cambio'  => $motivo,
            'creado_por'     => auth()->check() ? auth()->id() : null,
        ]);
    }

    public function clonarConItems(): self
    {
        return DB::transaction(function (): self {
            $clon = self::create([
                'agencia_id'         => $this->agencia_id,
                'responsable_id'     => $this->responsable_id,
                'estado'             => 'borrador',
                'version'            => 1,
                'fecha_emision'      => $this->fecha_emision,
                'fecha_vencimiento'  => $this->fecha_vencimiento,
                'metodo_pago'        => $this->metodo_pago,
                'logistica_instalacion_propia' => $this->logistica_instalacion_propia,
                'logistica_leyenda'  => $this->logistica_leyenda,
                'logistica_costo'    => $this->logistica_costo,
                'observaciones'      => $this->observaciones,
                'notas_internas'     => $this->notas_internas,
                'datos_adicionales'  => $this->datos_adicionales,
            ]);

            $this->items()
                ->orderBy('orden')
                ->get()
                ->each(function (PresupuestoItem $item) use ($clon): void {
                    $clon->items()->create([
                        'mobiliario_id'          => $item->mobiliario_id,
                        'insumo_id'              => $item->insumo_id,
                        'sector_id'              => $item->sector_id,
                        'cantidad'               => $item->cantidad,
                        'precio_unitario'        => $item->precio_unitario,
                        'descripcion_override'   => $item->descripcion_override,
                        'observaciones'          => $item->observaciones,
                        'notas_manuales'         => $item->notas_manuales,
                        'orden'                  => $item->orden,
                    ]);
                });

            return $clon;
        });
    }

    // ─── State helpers ────────────────────────────────────────────────────────

    public function puedeEnviarARevision(): bool
    {
        return $this->estado === 'borrador';
    }

    public function puedeAprobar(): bool
    {
        return $this->estado === 'en_revision';
    }

    public function puedeRechazar(): bool
    {
        return $this->estado === 'en_revision';
    }

    public function puedeCancelar(): bool
    {
        return in_array($this->estado, ['borrador', 'en_revision', 'aprobado', 'confirmado'], true);
    }

    public function puedeRegistrarEntrega(): bool
    {
        return in_array($this->estado, ['confirmado', 'pagado', 'entregado_parcial', 'entregado'], true);
    }

    public function getItemsEntregadosCountAttribute(): int
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();

        return $items->filter(fn (PresupuestoItem $item) => $item->estaEntregado())->count();
    }

    public function getProgresoEntregaAttribute(): string
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();
        $total = $items->count();

        if ($total === 0) {
            return '0/0';
        }

        return $this->items_entregados_count . '/' . $total;
    }

    public function getResumenEntregaAttribute(): string
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();
        $total = $items->count();
        $entregados = $this->items_entregados_count;

        if ($total === 0 || $entregados === 0) {
            return 'Sin entregas';
        }

        if ($entregados === $total) {
            return 'Entrega completa';
        }

        return 'Entrega parcial';
    }

    public function puedeEditar(): bool
    {
        return in_array($this->estado, ['borrador','en_revision', 'rechazado']);
    }

    // ─── Relations ────────────────────────────────────────────────────────────

    /** El proyecto se obtiene a través de la agencia asignada. */
    public function proyecto(): HasOneThrough
    {
        return $this->hasOneThrough(
            Proyecto::class,
            Agencia::class,
            'id',
            'id',
            'agencia_id',
            'proyecto_id',
        );
    }

    public function agencia(): BelongsTo
    {
        return $this->belongsTo(Agencia::class, 'agencia_id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function aprobadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PresupuestoItem::class)->orderBy('orden');
    }

    public function versiones(): HasMany
    {
        return $this->hasMany(PresupuestoVersion::class)->orderByDesc('numero_version');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(PresupuestoHistorial::class)->latest();
    }

    public function reservasStock(): HasMany
    {
        return $this->hasMany(ReservaStock::class);
    }

    public function ordenesCompra(): HasMany
    {
        return $this->hasMany(OrdenCompra::class);
    }

    public function esConfirmado(): bool
    {
        return in_array($this->estado, ['confirmado', 'pagado', 'entregado_parcial', 'entregado'], true);
    }

    public function layoutMedia(): ?\Spatie\MediaLibrary\MediaCollections\Models\Media
    {
        return $this->agencia?->getFirstMedia('planos');
    }

    public function tieneLayout(): bool
    {
        return $this->layoutMedia() !== null;
    }

    public function layoutUrl(): ?string
    {
        $media = $this->layoutMedia();

        return $media ? url($media->getUrl()) : null;
    }

    public static function textoBasesCondicionesDefault(?string $formaPago = null, int $diasEntrega = 45): string
    {
        $formaPago ??= self::TEXTO_PAGO_DEFECTO;
        $diasEntrega = $diasEntrega > 0 ? $diasEntrega : 50;

        return implode("\n", [
            'EL PRESUPUESTO TENDRÁ VALIDEZ DE 5 DÍAS.',
            'FORMA DE PAGO: ' . $formaPago,
            "PLAZO DE ENTREGA: {$diasEntrega} días de realizado el pago del mobiliario.",
            'LA TOTALIDAD DEL MOBILIARIO ESTA REALIZADO BAJO LOS REQUERIMIENTOS DE LA MARCA, TANTO EN LOS MATERIALES UTILIZADOS, COLORES, CALIDAD Y DISEÑO DE LOS MISMOS.',
            'EN EL SUPUESTO CASO QUE EL MOBILIARIO NO PUEDA INSTALARSE DEBERÁ SER DESEMBALADO Y CONTROLADO AL MOMENTO DE LA ENTREGA EN CONJUNTO CON LA PARTE VENDEDORA Y COMPRADORA. DE LO CONTRARIO LA EMPRESA NO SE RESPONSABILIZA POR LOS DAÑOS QUE SUFRA EL MISMO.',
            'LAS IMÁGENES SON ILUSTRATIVAS, PUEDEN VARIAR CON EL PRODUCTO REAL.',
            'En el supuesto caso de necesitar embalaje para el envío por transporte ajeno a nuestra empresa, se deberá adicionar un 10% sobre el producto.',
        ]);
    }

    public function basesCondicionesLineas(): array
    {
        $texto = trim((string) ($this->metodo_pago ?? ''));

        if ($texto === '' || in_array($texto, ['defecto', 'transferencia'], true)) {
            $texto = static::textoBasesCondicionesDefault(
                $texto === 'transferencia' ? 'TRANSFERENCIA.' : null
            );
        }

        return array_values(array_filter(
            preg_split('/\r\n|\r|\n/', $texto) ?: [],
            fn (string $linea): bool => trim($linea) !== ''
        ));
    }

    public function getLeyendaLogisticaEfectivaAttribute(): string
    {
        if ($this->logistica_instalacion_propia) {
            return self::LEYENDA_LOGISTICA_PROPIA;
        }

        return trim((string) ($this->logistica_leyenda ?? ''));
    }

    public function getLogisticaCostoNumericoAttribute(): float
    {
        return round((float) ($this->logistica_costo ?? 0), 2);
    }
}
