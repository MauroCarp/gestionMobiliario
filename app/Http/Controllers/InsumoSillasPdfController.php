<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InsumoSillasPdfController extends Controller
{
    use AuthorizesRequests;

    public function __invoke()
    {
        $this->authorize('viewAny', Insumo::class);

        $sillas = Insumo::query()
            ->whereHas(
                'categoriasInsumo',
                fn (Builder $query) => $query->whereRaw("LOWER(nombre) LIKE '%silla%'")
            )
            ->whereRaw("LOWER(insumos.nombre) NOT LIKE '%tela%'")
            ->whereRaw("LOWER(insumos.nombre) NOT LIKE '%casco%'")
            ->whereRaw("LOWER(insumos.nombre) NOT LIKE '%cuerina%'")
            ->whereRaw("LOWER(insumos.nombre) NOT LIKE '%espuma%'")
            ->whereRaw("LOWER(insumos.nombre) NOT LIKE '%leap%'")
            ->where(function (Builder $query) {
                $query
                ->whereRaw("LOWER(insumos.nombre) NOT LIKE '%base%'")
                    ->orWhereRaw("LOWER(insumos.nombre) LIKE '%silla%'");
            })
            ->with([
                'categoriasInsumo',
                'tipoSilla',
                'proveedor',
                'marcasSilla.marca',
                'media',
            ])
            ->orderBy('nombre')
            ->get()
            ->map(fn (Insumo $insumo): array => [
                'insumo'        => $insumo,
                'imagen_base64' => $this->imagenBase64($insumo),
            ]);

        $pdf = Pdf::loadView('pdf.insumos_sillas', [
            'sillas' => $sillas,
            'fecha'  => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('insumos-sillas.pdf');
    }

    private function imagenBase64(Insumo $insumo): ?string
    {
        try {
            $media = $insumo->getFirstMedia('imagen');

            if (! $media || ! file_exists($media->getPath())) {
                return null;
            }

            return 'data:' . $media->mime_type . ';base64,' . base64_encode(file_get_contents($media->getPath()));
        } catch (\Throwable) {
            return null;
        }
    }
}
