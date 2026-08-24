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
            'logistica_instalacion_propia' => false,
            'logistica_leyenda' => 'Logística externa',
            'logistica_costo' => 1000,
        ]);

        $html = view('pdf.presupuesto', [
            'presupuesto' => $presupuesto,
            'agencia' => null,
            'marca' => null,
            'logoBase64' => null,
            'logoEmpresaBase64' => null,
            'logisticaImagenBase64' => null,
            'itemsPorSector' => new Collection,
        ])->render();

        $this->assertStringContainsString('Logística externa', $html);
        $this->assertStringContainsString('SUB-TOTAL GENERAL:', $html);
        $this->assertStringContainsString('$1.000,00', $html);
    }
}
