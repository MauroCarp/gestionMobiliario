<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Mobiliario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
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
        $marca = null;

        $query = Mobiliario::query()
            ->withoutGlobalScopes()
            ->with(['atributos', 'marca', 'media'])
            ->whereHas('presupuestoItems', fn (Builder $query) => $this->applyPendienteEntregaConstraint($query))
            ->withSum(['presupuestoItems as cantidad_pendiente_entrega' => function (Builder $query): void {
                $this->applyPendienteEntregaConstraint($query);
            }], 'cantidad')
            ->orderBy('nombre');

        if (is_string($marcaTab) && str_starts_with($marcaTab, 'marca_')) {
            $marcaId = (int) substr($marcaTab, 6);
            $query->where('marca_id', $marcaId);
            $marca = Marca::find($marcaId);
        } elseif ($marcaTab === 'sin_marca') {
            $query->whereNull('marca_id');
        }

        $mobiliarios = $query->get()->map(function (Mobiliario $mobiliario): array {
            $items = $mobiliario->presupuestoItemsPendientesEntrega()
                ->with([
                    'presupuesto.agencia.proyecto.marca',
                    'mobiliario',
                    'sector',
                    'etapasProduccion',
                ])
                ->orderBy('presupuesto_id')
                ->orderBy('orden')
                ->get();

            return [
                'mobiliario'    => $mobiliario,
                'imagen_base64' => $this->imagenBase64($mobiliario),
                'atributos'     => $mobiliario->atributos->isNotEmpty()
                    ? $mobiliario->atributos->map(fn ($a) => "{$a->clave}: {$a->valor}")->join(' · ')
                    : '—',
                'cantidad'      => (int) ($mobiliario->cantidad_pendiente_entrega ?? 0),
                'items'         => $items,
            ];
        });

        $pdf = Pdf::loadView('pdf.mobiliarios_pendientes_entrega', [
            'mobiliarios' => $mobiliarios,
            'marca'       => $marca,
            'marcaTab'    => $marcaTab,
            'fecha'       => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('mobiliarios-pendientes-entrega.pdf');
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
