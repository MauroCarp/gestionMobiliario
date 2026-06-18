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
            'items' => fn ($q) => $q->orderBy('sector_id')->orderBy('orden')->with(['mobiliario', 'sector', 'insumo']),
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

        // Items con imagen en base64, agrupados por sector
        $items = $presupuesto->items->map(function ($item) {
            $imagenBase64 = null;
            try {
                $media = $item->mobiliario?->getFirstMedia('imagenes');
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
            'presupuesto', 'proyecto', 'agencia', 'marca', 'logoBase64', 'logoEmpresaBase64', 'items', 'itemsPorSector'
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
        $presupuesto->load(['proyecto.marca', 'agencia', 'responsable', 'items.mobiliario']);
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
                    'mobiliario.composicionTecnica.insumo.unidadMedida',
                    'mobiliario.plantillaFlujos' => fn ($q) => $q
                        ->where('activo', true)
                        ->with(['etapas.tipoProceso', 'etapas.tercero']),
                    'sector',
                    'insumo',
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
                $media = $item->mobiliario?->getFirstMedia('imagenes');
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

        $agencia    = $presupuesto->agencia;
        $planoUrl   = null;
        $planoDebug = [
            'agencia_id'         => $agencia?->id,
            'agencia_nombre'     => $agencia?->nombre,
            'total_media'        => $agencia?->media->count(),
            'media_collections'  => $agencia?->media->pluck('collection_name', 'id'),
            'media_filenames'    => $agencia?->media->pluck('file_name', 'id'),
        ];

        $planoMedia = $agencia?->getFirstMedia('planos');
        if ($planoMedia) {
            $rawUrl   = $planoMedia->getUrl();
            $absUrl   = url($rawUrl);
            $diskPath = $planoMedia->getPath();
            $planoUrl = $absUrl;
            $planoDebug['plano_found']     = true;
            $planoDebug['plano_raw_url']   = $rawUrl;
            $planoDebug['plano_abs_url']   = $absUrl;
            $planoDebug['plano_disk_path'] = $diskPath;
            $planoDebug['plano_file_exists'] = file_exists($diskPath);
            $planoDebug['plano_mime']      = $planoMedia->mime_type;
        } else {
            $planoDebug['plano_found'] = false;
        }

        return view('pdf.produccion_viewer', compact('codigo', 'pdfUrl', 'filename', 'planoUrl', 'planoDebug'));
    }
}
