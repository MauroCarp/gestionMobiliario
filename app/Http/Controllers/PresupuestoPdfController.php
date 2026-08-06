<?php

namespace App\Http\Controllers;

use App\Exports\PresupuestoExport;
use App\Exports\PresupuestoProduccionExport;
use App\Models\Presupuesto;
use App\Services\PresupuestoProduccionExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PresupuestoPdfController extends Controller
{
    public function show(Presupuesto $presupuesto)
    {
        $presupuesto->load([
            'agencia.proyecto.marca',
            'agencia.provincia',
            'agencia.ciudad',
            'responsable',
            'aprobadoPor',
            'items' => fn ($q) => $q->orderBy('sector_id')->orderBy('orden')->with([
                'mobiliario.atributos',
                'mobiliario.media',
                'sector',
                'insumo.media',
                'insumo.marcasSilla',
                'insumo.categoriasInsumo',
            ]),
        ]);

        // El proyecto y la marca se obtienen a través de la agencia
        $agencia  = $presupuesto->agencia;
        $proyecto = $agencia?->proyecto;
        $marca    = $proyecto?->marca;

        // Logo de la marca (se mantiene para uso secundario si fuera necesario)
        $logoBase64 = null;
        if ($marca && $marca->logo) {
            $logoPath = public_path('storage/' . ltrim($marca->logo, '/'));
            if (file_exists($logoPath)) {
                $mime       = mime_content_type($logoPath);
                $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
            }
        }

        // Logo de nuestra empresa (public/images/logo-empresa.png)
        $logoEmpresaBase64 = null;
        $logoEmpresaPath   = public_path('images/logo-empresa.png');
        if (file_exists($logoEmpresaPath)) {
            $mime              = mime_content_type($logoEmpresaPath);
            $logoEmpresaBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoEmpresaPath));
        }

        $logisticaImagenBase64 = null;
        $logisticaImagenPath = storage_path('app/public/logistica_instalacion/logistica_instalacion.png');
        if (file_exists($logisticaImagenPath)) {
            $mime = mime_content_type($logisticaImagenPath) ?: 'image/png';
            $logisticaImagenBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logisticaImagenPath));
        }

        // Items con imagen en base64, agrupados por sector
        $items = $presupuesto->items->map(function ($item) {
            $imagenBase64 = null;
            try {
                $media = $item->mobiliario
                    ? $item->mobiliario->getFirstMedia('imagenes')
                    : $item->insumo?->getFirstMedia('imagen');
                if ($media) {
                    $path = $media->getPath();
                    if (file_exists($path)) {
                        $imagenBase64 = 'data:' . $media->mime_type . ';base64,' . base64_encode(file_get_contents($path));
                    }
                }
            } catch (\Throwable) {}

            return [
                'item'          => $item,
                'mobiliario'    => $item->mobiliario,
                'insumo'        => $item->insumo,
                'sector'        => $item->sector,
                'imagen_base64' => $imagenBase64,
            ];
        });

        // Agrupar por sector (null = "Sin sector")
        $itemsPorSector = $items->groupBy(function ($itemData) {
            return $itemData['sector']?->nombre ?? '__sin_sector__';
        });

        $pdf = Pdf::loadView('pdf.presupuesto', compact(
            'presupuesto', 'proyecto', 'agencia', 'marca', 'logoBase64', 'logoEmpresaBase64', 'logisticaImagenBase64', 'items', 'itemsPorSector'
        ))
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'dpi'         => 150,
            'defaultFont' => 'DejaVu Sans',
            'isRemoteEnabled' => false,
        ]);

        $filename = "presupuesto-{$presupuesto->codigo}.pdf";

        return $pdf->stream($filename);
    }

    /**
     * Visor HTML que embebe el PDF para que se muestre inline en el navegador
     * sin depender de la configuración "descargar PDFs" del navegador.
     */
    public function viewer(Presupuesto $presupuesto)
    {
        $codigo   = $presupuesto->codigo;
        $pdfUrl   = route('presupuesto.pdf', $presupuesto);
        $filename = "presupuesto-{$codigo}.pdf";

        return view('pdf.viewer', compact('codigo', 'pdfUrl', 'filename'));
    }

    public function excel(Presupuesto $presupuesto)
    {
        $presupuesto->load([
            'proyecto.marca',
            'agencia.proyecto.marca',
            'agencia',
            'responsable',
            'items' => fn ($q) => $q->orderBy('orden')->with([
                'mobiliario.categoria',
                'insumo.marcasSilla',
                'insumo.categoriasInsumo',
            ]),
        ]);
        $filename = "presupuesto-{$presupuesto->codigo}.xlsx";

        return Excel::download(new PresupuestoExport($presupuesto), $filename);
    }

    public function produccionPdf(Presupuesto $presupuesto)
    {
        $presupuesto->load([
            'agencia.proyecto.marca',
            'agencia.provincia',
            'agencia.ciudad',
            'responsable',
            'aprobadoPor',
            'items' => fn ($q) => $q->orderBy('sector_id')->orderBy('orden')
                ->with([
                    'mobiliario.categoria',
                    'mobiliario.atributos',
                    'mobiliario.media',
                    'mobiliario.composicionTecnica.insumo.unidadMedida',
                    'mobiliario.plantillaFlujos' => fn ($q) => $q
                        ->where('activo', true)
                        ->with(['etapas.tipoProceso', 'etapas.tercero']),
                    'sector',
                    'insumo.media',
                    'insumo.marcasSilla',
                    'insumo.categoriasInsumo',
                ]),
        ]);

        $agencia  = $presupuesto->agencia;
        $proyecto = $agencia?->proyecto;
        $marca    = $proyecto?->marca;

        $logoBase64 = null;
        if ($marca && $marca->logo) {
            $logoPath = public_path('storage/' . ltrim($marca->logo, '/'));
            if (file_exists($logoPath)) {
                $mime       = mime_content_type($logoPath);
                $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
            }
        }

        $logoEmpresaBase64 = null;
        $logoEmpresaPath   = public_path('images/logo-empresa.png');
        if (file_exists($logoEmpresaPath)) {
            $mime              = mime_content_type($logoEmpresaPath);
            $logoEmpresaBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoEmpresaPath));
        }

        $items = $presupuesto->items->map(function ($item) {
            $imagenBase64 = null;
            try {
                $media = $item->mobiliario
                    ? $item->mobiliario->getFirstMedia('imagenes')
                    : $item->insumo?->getFirstMedia('imagen');
                if ($media) {
                    $path = $media->getPath();
                    if (file_exists($path)) {
                        $imagenBase64 = 'data:' . $media->mime_type . ';base64,' . base64_encode(file_get_contents($path));
                    }
                }
            } catch (\Throwable) {}

            return [
                'item'          => $item,
                'mobiliario'    => $item->mobiliario,
                'insumo'        => $item->insumo,
                'sector'        => $item->sector,
                'imagen_base64' => $imagenBase64,
            ];
        });

        $itemsPorSector = $items->groupBy(function ($itemData) {
            return $itemData['sector']?->nombre ?? '__sin_sector__';
        });

        $pdf = Pdf::loadView('pdf.produccion', compact(
            'presupuesto', 'proyecto', 'agencia', 'marca', 'logoBase64', 'logoEmpresaBase64', 'items', 'itemsPorSector'
        ))
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'dpi'             => 150,
            'defaultFont'     => 'DejaVu Sans',
            'isRemoteEnabled' => false,
        ]);

        $filename = "produccion-{$presupuesto->codigo}.pdf";

        return $pdf->stream($filename);
    }

    public function produccionExcel(Presupuesto $presupuesto, PresupuestoProduccionExportService $exportService)
    {
        $data     = $exportService->prepare($presupuesto);
        $filename = "produccion-{$presupuesto->codigo}.xlsx";

        return Excel::download(new PresupuestoProduccionExport($data), $filename);
    }

    public function produccionViewer(Presupuesto $presupuesto)
    {
        $presupuesto->load(['agencia.media']);

        $codigo   = $presupuesto->codigo;
        $pdfUrl   = route('presupuesto.produccion.pdf', $presupuesto);
        $filename = "produccion-{$codigo}.pdf";
        $planoUrl = $presupuesto->layoutUrl();

        $agencia    = $presupuesto->agencia;
        $planoDebug = [
            'agencia_id'         => $agencia?->id,
            'agencia_nombre'     => $agencia?->nombre,
            'total_media'        => $agencia?->media->count(),
            'media_collections'  => $agencia?->media->pluck('collection_name', 'id'),
            'media_filenames'    => $agencia?->media->pluck('file_name', 'id'),
            'plano_found'        => $planoUrl !== null,
        ];

        return view('pdf.produccion_viewer', compact('codigo', 'pdfUrl', 'filename', 'planoUrl', 'planoDebug'));
    }

    public function layout(Presupuesto $presupuesto)
    {
        $url = $presupuesto->layoutUrl();

        if (! $url) {
            abort(404, 'No hay layout asociado a la agencia de este presupuesto.');
        }

        return redirect($url);
    }
}
