<?php

namespace App\Http\Controllers;

use App\Exports\PresupuestoExport;
use App\Exports\PresupuestoProduccionExport;
use App\Models\Presupuesto;
use App\Services\PresupuestoPdfCache;
use App\Services\PresupuestoProduccionExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Maatwebsite\Excel\Facades\Excel;

class PresupuestoPdfController extends Controller
{
    use AuthorizesRequests;

    protected function mostrarPreciosEnPdf(): bool
    {
        return ! in_array(request()->query('precios', '1'), ['0', 'false', 'no'], true);
    }

    public function show(Presupuesto $presupuesto, PresupuestoPdfCache $cache)
    {
        $this->authorize('exportComercial', $presupuesto);

        $presupuesto->load([
            'agencia.proyecto.marca',
            'agencia.provincia',
            'agencia.ciudad',
            'responsable',
            'aprobadoPor',
            'items' => fn ($q) => $q->reorder('sector_id')->orderBy('orden')->with([
                'mobiliario.atributos',
                'mobiliario.categoria',
                'mobiliario.media',
                'sector',
                'insumo.media',
                'insumo.marcasSilla',
                'insumo.categoriasInsumo',
            ]),
        ]);

        $mostrarPrecios = $this->mostrarPreciosEnPdf();

        return $this->responderPdf(
            $cache,
            $presupuesto,
            'comercial',
            $mostrarPrecios,
            "presupuesto-{$presupuesto->codigo}.pdf",
            fn () => Pdf::loadView('pdf.presupuesto', array_merge(
                $this->datosVista($presupuesto, $cache, 0.15),
                [
                    'logisticaImagenBase64' => $cache->archivoABase64(
                        storage_path('app/public/logistica_instalacion/logistica_instalacion.png'),
                        'image/png'
                    ),
                    'mostrarPrecios' => $mostrarPrecios,
                ]
            ))->setPaper('a4', 'portrait')->setOptions($this->opcionesPdf())->output()
        );
    }

    /**
     * Visor HTML que embebe el PDF para que se muestre inline en el navegador
     * sin depender de la configuración "descargar PDFs" del navegador.
     */
    public function viewer(Presupuesto $presupuesto)
    {
        $this->authorize('exportComercial', $presupuesto);

        $codigo      = $presupuesto->codigo;
        $precios     = request()->query('precios', '1');
        $autoguardar = in_array(request()->query('autoguardar', '0'), ['1', 'true', 'si'], true);
        $pdfUrl      = route('presupuesto.pdf', [
            'presupuesto' => $presupuesto,
            'precios' => $precios,
        ]);
        $filename = "presupuesto-{$codigo}.pdf";

        return view('pdf.viewer', compact('codigo', 'pdfUrl', 'filename', 'autoguardar'));
    }

    public function excel(Presupuesto $presupuesto)
    {
        $this->authorize('export', $presupuesto);

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

    public function produccionPdf(Presupuesto $presupuesto, PresupuestoPdfCache $cache)
    {
        $this->authorize('exportProduccion', $presupuesto);

        $presupuesto->load([
            'agencia.proyecto.marca',
            'agencia.provincia',
            'agencia.ciudad',
            'responsable',
            'aprobadoPor',
            'items' => fn ($q) => $q->reorder('sector_id')->orderBy('orden')
                ->with([
                    'mobiliario.categoria',
                    'mobiliario.atributos',
                    'mobiliario.media',
                    'mobiliario.composicionTecnica.insumo.unidadMedida',
                    'mobiliario.plantillaFlujos' => fn ($q) => $q
                        ->where('activo', true)
                        ->with(['etapas.tipoProceso', 'etapas.tercero']),
                    'sector',
                    'insumo.unidadMedida',
                    'insumo.media',
                    'insumo.marcasSilla',
                    'insumo.categoriasInsumo',
                ]),
        ]);

        return $this->responderPdf(
            $cache,
            $presupuesto,
            'produccion',
            null,
            "produccion-{$presupuesto->codigo}.pdf",
            fn () => Pdf::loadView(
                'pdf.produccion',
                $this->datosVista($presupuesto, $cache, 0.08)
            )->setPaper('a4', 'portrait')->setOptions($this->opcionesPdf())->output()
        );
    }

    public function produccionExcel(Presupuesto $presupuesto, PresupuestoProduccionExportService $exportService)
    {
        $this->authorize('export', $presupuesto);

        $data     = $exportService->prepare($presupuesto);
        $filename = "produccion-{$presupuesto->codigo}.xlsx";

        return Excel::download(new PresupuestoProduccionExport($data), $filename);
    }

    public function produccionViewer(Presupuesto $presupuesto)
    {
        $this->authorize('exportProduccion', $presupuesto);

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

    /**
     * @param  callable(): string  $generar
     */
    private function responderPdf(
        PresupuestoPdfCache $cache,
        Presupuesto $presupuesto,
        string $tipo,
        ?bool $conPrecios,
        string $filename,
        callable $generar,
    ) {
        $opciones = $this->opcionesPdf();
        $huella = $cache->huella($presupuesto, $tipo, $conPrecios, $opciones);
        $clave = $cache->clave($presupuesto, $tipo, $conPrecios);
        $path = $cache->rutaSiCoincide($clave, $huella) ?? $cache->guardar($clave, $huella, $generar());

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
            'Cache-Control' => 'private, no-cache, must-revalidate',
        ]);
    }

    private function datosVista(Presupuesto $presupuesto, PresupuestoPdfCache $cache, float $opacidadMarcaDeAgua): array
    {
        $agencia = $presupuesto->agencia;
        $proyecto = $agencia?->proyecto;
        $marca = $proyecto?->marca;

        $logoBase64 = null;
        if ($marca?->logo) {
            $logoBase64 = $cache->archivoABase64(public_path('storage/'.ltrim($marca->logo, '/')));
        }

        $items = $presupuesto->items->map(fn ($item) => [
            'item' => $item,
            'mobiliario' => $item->mobiliario,
            'insumo' => $item->insumo,
            'sector' => $item->sector,
            'imagen_base64' => $cache->imagenItemBase64($item),
        ]);

        return [
            'presupuesto' => $presupuesto,
            'proyecto' => $proyecto,
            'agencia' => $agencia,
            'marca' => $marca,
            'logoBase64' => $logoBase64,
            'logoEmpresaBase64' => $cache->archivoABase64(public_path('images/logo-empresa.png')),
            'marcaDeAguaBase64' => $cache->marcaDeAguaBase64($opacidadMarcaDeAgua),
            'items' => $items,
            'itemsPorSector' => $items->groupBy(fn ($itemData) => $itemData['sector']?->nombre ?? '__sin_sector__'),
        ];
    }

    private function opcionesPdf(): array
    {
        return [
            'dpi' => 150,
            'defaultFont' => 'DejaVu Sans',
            'isRemoteEnabled' => false,
        ];
    }
}
