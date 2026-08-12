<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Mobiliario;
use App\Services\PresupuestoItemProduccionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class MobiliarioPendientesEntregaPdfController extends Controller
{
    private const ESTADOS_PRESUPUESTO_PENDIENTES_ENTREGA = [
        'confirmado',
        'pagado',
        'entregado_parcial',
    ];

    public function __invoke(Request $request)
    {
        $marcaTab = $request->query('marca', 'todos');
        $etapa = $request->query('etapa', 'todas');
        $marca = null;

        $query = Mobiliario::query()
            ->withoutGlobalScopes()
            ->with(['atributos', 'marcas', 'media'])
            ->whereHas('presupuestoItems', fn (Builder $query) => $this->applyItemFilters($query, $etapa))
            ->withSum(['presupuestoItems as cantidad_pendiente_entrega' => function (Builder $query) use ($etapa): void {
                $this->applyItemFilters($query, $etapa);
            }], 'cantidad')
            ->orderBy('nombre');

        if (is_string($marcaTab) && str_starts_with($marcaTab, 'marca_')) {
            $marcaId = (int) substr($marcaTab, 6);
            $query->whereHas(
                'marcas',
                fn (Builder $marcasQuery) => $marcasQuery->where('marcas.id', $marcaId),
            );
            $marca = Marca::find($marcaId);
        } elseif ($marcaTab === 'sin_marca') {
            $query->whereDoesntHave('marcas');
        }

        $mobiliarios = $query->get()
            ->map(function (Mobiliario $mobiliario) use ($etapa): ?array {
                $itemsQuery = $mobiliario->presupuestoItemsPendientesEntrega();
                $this->applyEtapaActualFilter($itemsQuery, $etapa);

                $items = $itemsQuery
                    ->with([
                        'presupuesto.agencia.proyecto.marca',
                        'mobiliario',
                        'sector',
                        'etapasProduccion',
                    ])
                    ->orderBy('presupuesto_id')
                    ->orderBy('orden')
                    ->get();

                if ($items->isEmpty()) {
                    return null;
                }

                return [
                    'mobiliario'    => $mobiliario,
                    'imagen_base64' => $this->imagenBase64($mobiliario),
                    'atributos'     => $mobiliario->atributos->isNotEmpty()
                        ? $mobiliario->atributos->map(fn ($a) => "{$a->clave}: {$a->valor}")->join(' · ')
                        : '—',
                    'cantidad'      => (int) $items->sum('cantidad'),
                    'items'         => $items,
                ];
            })
            ->filter()
            ->values();

        $pdf = Pdf::loadView('pdf.mobiliarios_pendientes_entrega', [
            'mobiliarios' => $mobiliarios,
            'marca'       => $marca,
            'marcaTab'    => $marcaTab,
            'etapa'       => $etapa,
            'fecha'       => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('mobiliarios-pendientes-entrega.pdf');
    }

    private function applyItemFilters(Builder $query, string $etapa): void
    {
        $this->applyPendienteEntregaConstraint($query);
        $this->applyEtapaActualFilter($query, $etapa);
    }

    private function applyPendienteEntregaConstraint(Builder $query): void
    {
        $query
            ->whereNull('entregado_at')
            ->whereHas('presupuesto', fn (Builder $presupuestoQuery) => $presupuestoQuery->whereIn(
                'estado',
                self::ESTADOS_PRESUPUESTO_PENDIENTES_ENTREGA,
            ));
    }

    private function applyEtapaActualFilter(Builder|Relation $query, string $etapa): void
    {
        if ($etapa === 'todas') {
            return;
        }

        if ($etapa === 'Completado') {
            $query
                ->whereHas('etapasProduccion')
                ->whereDoesntHave('etapasProduccion', fn (Builder $etapasQuery) => $etapasQuery->where('estado', '!=', 'completado'));

            return;
        }

        if (! in_array($etapa, PresupuestoItemProduccionService::ETAPAS_PREDETERMINADAS, true)) {
            return;
        }

        $query->whereRaw('(
            SELECT pie.nombre
            FROM presupuesto_item_etapas AS pie
            WHERE pie.presupuesto_item_id = presupuesto_items.id
              AND pie.estado != ?
            ORDER BY pie.orden ASC
            LIMIT 1
        ) = ?', ['completado', $etapa]);
    }

    private function imagenBase64(Mobiliario $mobiliario): ?string
    {
        try {
            $media = $mobiliario->getFirstMedia('imagenes');

            if (! $media || ! file_exists($media->getPath())) {
                return null;
            }

            return 'data:' . $media->mime_type . ';base64,' . base64_encode(file_get_contents($media->getPath()));
        } catch (\Throwable) {
            return null;
        }
    }
}
