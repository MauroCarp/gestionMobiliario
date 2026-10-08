<?php

namespace Tests\Feature;

use App\Models\CategoriaInsumo;
use App\Models\Insumo;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PresupuestoLogisticaViewsTest extends TestCase
{
    #[Test]
    public function excel_no_incluye_la_fila_de_logistica(): void
    {
        $presupuesto = new Presupuesto([
            'codigo' => 'PRES-2026-0001',
            'version' => 1,
            'estado' => 'borrador',
            'fecha_emision' => Carbon::parse('2026-08-03'),
            'logistica_instalacion_propia' => true,
            'logistica_costo' => 1500,
        ]);
        $presupuesto->setRelation('items', collect());

        $html = view('exports.presupuesto_excel', [
            'presupuesto' => $presupuesto,
        ])->render();

        $this->assertStringNotContainsString(Presupuesto::LEYENDA_LOGISTICA_PROPIA, $html);
        $this->assertStringNotContainsString('1500', $html);
        $this->assertStringNotContainsString('Total estimado:', $html);
    }

    #[Test]
    public function pdf_incluye_logistica_en_subtotal_general(): void
    {
        $presupuesto = new Presupuesto([
            'codigo' => 'PRES-2026-0002',
            'fecha_emision' => Carbon::parse('2026-08-03'),
            'metodo_pago' => Presupuesto::textoBasesCondicionesDefault(),
            'logistica_instalacion_propia' => true,
            'logistica_costo' => 1000,
        ]);

        $html = view('pdf.presupuesto', [
            'presupuesto' => $presupuesto,
            'agencia' => null,
            'marca' => null,
            'logoBase64' => null,
            'logoEmpresaBase64' => null,
            'logisticaImagenBase64' => null,
            'armadoImagenBase64' => null,
            'itemsPorSector' => new Collection,
        ])->render();

        $this->assertStringContainsString(Presupuesto::LEYENDA_LOGISTICA_PROPIA, $html);
        $this->assertStringContainsString('SUB-TOTAL GENERAL:', $html);
        $this->assertStringContainsString('$1.000,00', $html);
    }

    #[Test]
    public function excel_no_incluye_logistica_cuando_no_esta_tildado(): void
    {
        $presupuesto = new Presupuesto([
            'codigo' => 'PRES-2026-0007',
            'version' => 1,
            'estado' => 'borrador',
            'fecha_emision' => Carbon::parse('2026-10-06'),
            'logistica_instalacion_propia' => false,
            'logistica_leyenda' => 'Logística externa',
            'logistica_costo' => 1500,
            'armado' => true,
            'armado_costo' => 800,
        ]);
        $presupuesto->setRelation('items', collect());

        $html = view('exports.presupuesto_excel', [
            'presupuesto' => $presupuesto,
        ])->render();

        $this->assertStringNotContainsString(Presupuesto::LEYENDA_LOGISTICA_PROPIA, $html);
        $this->assertStringNotContainsString('Logística externa', $html);
        $this->assertStringContainsString(Presupuesto::LEYENDA_ARMADO, $html);
        $this->assertStringContainsString('800,00', $html);
    }

    #[Test]
    public function pdf_no_incluye_logistica_cuando_no_esta_tildado(): void
    {
        $presupuesto = new Presupuesto([
            'codigo' => 'PRES-2026-0008',
            'fecha_emision' => Carbon::parse('2026-10-06'),
            'metodo_pago' => Presupuesto::textoBasesCondicionesDefault(),
            'logistica_instalacion_propia' => false,
            'logistica_leyenda' => 'Logística externa',
            'logistica_costo' => 1000,
            'armado' => true,
            'armado_costo' => 500,
        ]);

        $html = view('pdf.presupuesto', [
            'presupuesto' => $presupuesto,
            'agencia' => null,
            'marca' => null,
            'logoBase64' => null,
            'logoEmpresaBase64' => null,
            'logisticaImagenBase64' => null,
            'armadoImagenBase64' => null,
            'itemsPorSector' => new Collection,
        ])->render();

        $this->assertStringNotContainsString(Presupuesto::LEYENDA_LOGISTICA_PROPIA, $html);
        $this->assertStringNotContainsString('Logística externa', $html);
        $this->assertStringContainsString(Presupuesto::LEYENDA_ARMADO, $html);
        $this->assertStringContainsString('$500,00', $html);
        $this->assertStringNotContainsString('$1.000,00', $html);
    }

    #[Test]
    public function excel_incluye_la_fila_de_armado_y_suma_el_total(): void
    {
        $presupuesto = new Presupuesto([
            'codigo' => 'PRES-2026-0003',
            'version' => 1,
            'estado' => 'borrador',
            'fecha_emision' => Carbon::parse('2026-10-06'),
            'logistica_instalacion_propia' => true,
            'logistica_costo' => 1500,
            'armado' => true,
            'armado_costo' => 800,
        ]);
        $presupuesto->setRelation('items', collect());

        $html = view('exports.presupuesto_excel', [
            'presupuesto' => $presupuesto,
        ])->render();

        $this->assertStringContainsString(Presupuesto::LEYENDA_ARMADO, $html);
        $this->assertStringContainsString('800,00', $html);
        $this->assertStringNotContainsString(Presupuesto::LEYENDA_LOGISTICA_PROPIA, $html);
        $this->assertStringNotContainsString('2,300.00', $html);
    }

    #[Test]
    public function excel_no_incluye_armado_cuando_no_esta_tildado(): void
    {
        $presupuesto = new Presupuesto([
            'codigo' => 'PRES-2026-0004',
            'version' => 1,
            'estado' => 'borrador',
            'fecha_emision' => Carbon::parse('2026-10-06'),
            'logistica_instalacion_propia' => true,
            'logistica_costo' => 1500,
            'armado' => false,
            'armado_costo' => 800,
        ]);
        $presupuesto->setRelation('items', collect());

        $html = view('exports.presupuesto_excel', [
            'presupuesto' => $presupuesto,
        ])->render();

        $this->assertStringNotContainsString(Presupuesto::LEYENDA_ARMADO, $html);
        $this->assertStringNotContainsString('800', $html);
        $this->assertStringNotContainsString(Presupuesto::LEYENDA_LOGISTICA_PROPIA, $html);
        $this->assertStringNotContainsString('1,500.00', $html);
    }

    #[Test]
    public function pdf_incluye_armado_en_subtotal_general(): void
    {
        $presupuesto = new Presupuesto([
            'codigo' => 'PRES-2026-0005',
            'fecha_emision' => Carbon::parse('2026-10-06'),
            'metodo_pago' => Presupuesto::textoBasesCondicionesDefault(),
            'logistica_instalacion_propia' => false,
            'logistica_leyenda' => 'Logística externa',
            'logistica_costo' => 1000,
            'armado' => true,
            'armado_costo' => 500,
        ]);

        $html = view('pdf.presupuesto', [
            'presupuesto' => $presupuesto,
            'agencia' => null,
            'marca' => null,
            'logoBase64' => null,
            'logoEmpresaBase64' => null,
            'logisticaImagenBase64' => null,
            'armadoImagenBase64' => null,
            'itemsPorSector' => new Collection,
        ])->render();

        $this->assertStringContainsString(Presupuesto::LEYENDA_ARMADO, $html);
        $this->assertStringContainsString('$500', $html);
        $this->assertStringContainsString('$500,00', $html);
        $this->assertStringNotContainsString(Presupuesto::LEYENDA_LOGISTICA_PROPIA, $html);
    }

    #[Test]
    public function pdf_no_incluye_armado_cuando_no_esta_tildado(): void
    {
        $presupuesto = new Presupuesto([
            'codigo' => 'PRES-2026-0006',
            'fecha_emision' => Carbon::parse('2026-10-06'),
            'metodo_pago' => Presupuesto::textoBasesCondicionesDefault(),
            'logistica_instalacion_propia' => false,
            'logistica_leyenda' => 'Logística externa',
            'logistica_costo' => 1000,
            'armado' => false,
            'armado_costo' => 500,
        ]);

        $html = view('pdf.presupuesto', [
            'presupuesto' => $presupuesto,
            'agencia' => null,
            'marca' => null,
            'logoBase64' => null,
            'logoEmpresaBase64' => null,
            'logisticaImagenBase64' => null,
            'armadoImagenBase64' => null,
            'itemsPorSector' => new Collection,
        ])->render();

        $this->assertStringNotContainsString(Presupuesto::LEYENDA_ARMADO, $html);
        $this->assertStringNotContainsString('$500', $html);
        $this->assertStringNotContainsString(Presupuesto::LEYENDA_LOGISTICA_PROPIA, $html);
        $this->assertStringNotContainsString('Logística externa', $html);
        $this->assertStringNotContainsString('$1.000,00', $html);
    }

    #[Test]
    public function excel_muestra_costo_directo_de_silla_como_precio_del_insumo(): void
    {
        $insumo = new Insumo([
            'nombre' => 'Silla operativa',
            'codigo' => 'INS-SILLA',
            'precio_costo' => 1234.5,
        ]);
        $insumo->setRelation('categoriasInsumo', collect([
            new CategoriaInsumo(['nombre' => 'Sillas']),
        ]));
        $insumo->setRelation('marcasSilla', collect());

        $item = new PresupuestoItem([
            'cantidad' => 2,
            'precio_unitario' => 2000,
        ]);
        $item->setRelation('mobiliario', null);
        $item->setRelation('insumo', $insumo);

        $presupuesto = new Presupuesto([
            'codigo' => 'PRES-2026-0009',
            'version' => 1,
            'estado' => 'borrador',
            'fecha_emision' => Carbon::parse('2026-10-07'),
            'logistica_instalacion_propia' => false,
            'armado' => false,
        ]);
        $presupuesto->setRelation('items', collect([$item]));
        $presupuesto->setRelation('agencia', null);
        $presupuesto->setRelation('proyecto', null);
        $presupuesto->setRelation('responsable', null);

        $html = view('exports.presupuesto_excel', [
            'presupuesto' => $presupuesto,
        ])->render();

        $this->assertStringContainsString('Costo Directo Unitario', $html);
        $this->assertStringContainsString('Costo Directo Total', $html);
        $this->assertStringContainsString('Margen Bruto directo $', $html);
        $this->assertStringContainsString('Margen bruto directo %', $html);
        $this->assertStringNotContainsString('Descripción / Observaciones', $html);
        $this->assertStringContainsString('1.234,50', $html);
        $this->assertStringContainsString('2.469,00', $html);
        $this->assertStringContainsString('1.531,00', $html);
        $this->assertStringContainsString('38,28', $html);
        $this->assertStringContainsString('Silla operativa', $html);
    }

    #[Test]
    public function excel_muestra_margen_ponderado_en_el_total(): void
    {
        $sillaA = new Insumo(['nombre' => 'Silla A', 'precio_costo' => 1234.5]);
        $sillaA->setRelation('categoriasInsumo', collect([new CategoriaInsumo(['nombre' => 'Sillas'])]));
        $sillaA->setRelation('marcasSilla', collect());

        $itemA = new PresupuestoItem(['cantidad' => 2, 'precio_unitario' => 2000]);
        $itemA->setRelation('mobiliario', null);
        $itemA->setRelation('insumo', $sillaA);

        $sillaB = new Insumo(['nombre' => 'Silla B', 'precio_costo' => 800]);
        $sillaB->setRelation('categoriasInsumo', collect([new CategoriaInsumo(['nombre' => 'Sillas'])]));
        $sillaB->setRelation('marcasSilla', collect());

        $itemB = new PresupuestoItem(['cantidad' => 1, 'precio_unitario' => 1000]);
        $itemB->setRelation('mobiliario', null);
        $itemB->setRelation('insumo', $sillaB);

        $presupuesto = new Presupuesto([
            'codigo' => 'PRES-2026-0011',
            'version' => 1,
            'estado' => 'borrador',
            'fecha_emision' => Carbon::parse('2026-10-08'),
            'logistica_instalacion_propia' => true,
            'logistica_costo' => 1500,
            'armado' => false,
        ]);
        $presupuesto->setRelation('items', collect([$itemA, $itemB]));
        $presupuesto->setRelation('agencia', null);
        $presupuesto->setRelation('proyecto', null);
        $presupuesto->setRelation('responsable', null);

        $html = view('exports.presupuesto_excel', [
            'presupuesto' => $presupuesto,
        ])->render();

        $this->assertStringNotContainsString(Presupuesto::LEYENDA_LOGISTICA_PROPIA, $html);
        $this->assertStringContainsString('34,62', $html);
    }

    #[Test]
    public function visor_excel_incluye_la_tabla_y_el_link_de_descarga(): void
    {
        $presupuesto = new Presupuesto([
            'codigo' => 'PRES-2026-0010',
            'version' => 1,
            'estado' => 'borrador',
            'fecha_emision' => Carbon::parse('2026-10-07'),
            'logistica_instalacion_propia' => false,
            'armado' => false,
        ]);
        $presupuesto->setRelation('items', collect());
        $presupuesto->setRelation('agencia', null);
        $presupuesto->setRelation('proyecto', null);
        $presupuesto->setRelation('responsable', null);

        $html = view('exports.presupuesto_excel_viewer', [
            'presupuesto' => $presupuesto,
            'downloadUrl' => 'https://example.test/presupuesto/1/excel',
        ])->render();

        $this->assertStringContainsString('Descargar Excel', $html);
        $this->assertStringContainsString('https://example.test/presupuesto/1/excel', $html);
        $this->assertStringContainsString('Costo Directo Unitario', $html);
        $this->assertStringContainsString('PRES-2026-0010', $html);
    }
}
