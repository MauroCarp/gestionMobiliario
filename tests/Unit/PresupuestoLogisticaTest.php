<?php

namespace Tests\Unit;

use App\Models\Presupuesto;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PresupuestoLogisticaTest extends TestCase
{
    #[Test]
    public function usa_leyenda_fija_cuando_logistica_es_propia(): void
    {
        $presupuesto = new Presupuesto([
            'logistica_instalacion_propia' => true,
            'logistica_leyenda' => 'Texto que no debería usarse',
        ]);

        $this->assertSame(
            Presupuesto::LEYENDA_LOGISTICA_PROPIA,
            $presupuesto->leyenda_logistica_efectiva
        );
    }

    #[Test]
    public function usa_leyenda_personalizada_cuando_no_es_propia(): void
    {
        $presupuesto = new Presupuesto([
            'logistica_instalacion_propia' => false,
            'logistica_leyenda' => '  Instalación a cargo del cliente  ',
        ]);

        $this->assertSame('Instalación a cargo del cliente', $presupuesto->leyenda_logistica_efectiva);
    }

    #[Test]
    public function normaliza_el_costo_de_logistica(): void
    {
        $presupuesto = new Presupuesto([
            'logistica_costo' => '123.456',
        ]);

        $this->assertSame(123.46, $presupuesto->logistica_costo_numerico);
    }
}
