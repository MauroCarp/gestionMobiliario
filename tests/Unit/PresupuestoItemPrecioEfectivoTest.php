<?php

namespace Tests\Unit;

use App\Models\Mobiliario;
use App\Models\PresupuestoItem;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PresupuestoItemPrecioEfectivoTest extends TestCase
{
    #[Test]
    public function usa_precio_unitario_cuando_esta_cargado(): void
    {
        $item = new PresupuestoItem([
            'cantidad' => 2,
            'precio_unitario' => 1500,
        ]);
        $item->setRelation('mobiliario', new Mobiliario(['precio' => 900]));

        $this->assertSame(1500.0, $item->precioUnitarioEfectivo());
        $this->assertSame(3000.0, $item->subtotalEfectivo());
    }

    #[Test]
    public function usa_precio_de_lista_cuando_no_hay_precio_unitario(): void
    {
        $item = new PresupuestoItem([
            'cantidad' => 3,
            'precio_unitario' => null,
        ]);
        $item->setRelation('mobiliario', new Mobiliario(['precio' => 250]));

        $this->assertSame(250.0, $item->precioUnitarioEfectivo());
        $this->assertSame(750.0, $item->subtotalEfectivo());
    }

    #[Test]
    public function pdf_muestra_precio_de_lista_si_el_item_no_tiene_precio_unitario(): void
    {
        $item = new PresupuestoItem([
            'cantidad' => 2,
            'precio_unitario' => null,
        ]);
        $mobiliario = new Mobiliario(['nombre' => 'Mesa de prueba', 'precio' => 1200]);
        $mobiliario->setRelation('atributos', collect());
        $item->setRelation('mobiliario', $mobiliario);
        $item->setRelation('insumo', null);

        $html = view('pdf.presupuesto', [
            'presupuesto' => new \App\Models\Presupuesto([
                'codigo' => 'PRES-TEST',
                'fecha_emision' => now(),
                'metodo_pago' => 'defecto',
                'dias_entrega' => 50,
                'logistica_instalacion_propia' => false,
                'logistica_costo' => 0,
            ]),
            'agencia' => null,
            'marca' => null,
            'logoBase64' => null,
            'logoEmpresaBase64' => null,
            'logisticaImagenBase64' => null,
            'itemsPorSector' => collect([
                '__sin_sector__' => collect([
                    [
                        'item' => $item,
                        'mobiliario' => $mobiliario,
                        'insumo' => null,
                        'sector' => null,
                        'imagen_base64' => null,
                    ],
                ]),
            ]),
        ])->render();

        $this->assertStringContainsString('$1.200,00', $html);
        $this->assertStringContainsString('$2.400,00', $html);
        $this->assertStringContainsString('SUB-TOTAL GENERAL:', $html);
    }
}
