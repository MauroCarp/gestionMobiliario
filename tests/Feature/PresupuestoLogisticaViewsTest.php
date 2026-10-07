<?php

namespace Tests\Feature;

use App\Models\Presupuesto;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PresupuestoLogisticaViewsTest extends TestCase
{
    #[Test]
    public function excel_incluye_la_fila_de_logistica_y_suma_el_total(): void
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

        $this->assertStringContainsString(Presupuesto::LEYENDA_LOGISTICA_PROPIA, $html);
        $this->assertStringContainsString('1500', $html);
        $this->assertStringContainsString('Total estimado:', $html);
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
        $this->assertStringContainsString('800.00', $html);
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
        $this->assertStringContainsString('800', $html);
        $this->assertStringContainsString('2,300.00', $html);
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
        $this->assertStringContainsString('1,500.00', $html);
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
}
