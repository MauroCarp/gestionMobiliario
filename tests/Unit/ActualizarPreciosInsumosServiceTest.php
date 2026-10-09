<?php

namespace Tests\Unit;

use App\Models\Insumo;
use App\Services\ActualizarPreciosInsumosService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ActualizarPreciosInsumosServiceTest extends TestCase
{
    #[Test]
    public function aplica_el_porcentaje_y_redondea_a_dos_decimales(): void
    {
        $servicio = new ActualizarPreciosInsumosService();

        $this->assertSame(110.0, $servicio->calcularNuevoPrecio(100.0, 10));
        $this->assertSame(11.14, $servicio->calcularNuevoPrecio(10.125, 10));
    }

    #[Test]
    public function un_precio_nulo_no_produce_valor_nuevo(): void
    {
        $servicio = new ActualizarPreciosInsumosService();

        $this->assertNull($servicio->calcularNuevoPrecio(null, 10));
    }

    #[Test]
    public function aplica_precio_y_fecha_de_actualizacion_en_memoria(): void
    {
        $servicio = new ActualizarPreciosInsumosService();
        $insumo = new Insumo([
            'precio_costo' => 80,
            'fecha_ultimo_precio' => '2024-03-12',
        ]);

        $aplicado = $servicio->aplicarAInsumo($insumo, 25, '2026-10-09');

        $this->assertTrue($aplicado);
        $this->assertSame(100.0, $insumo->precio_costo);
        $this->assertSame('2026-10-09', $insumo->fecha_ultimo_precio?->toDateString());
    }

    #[Test]
    public function no_modifica_un_insumo_sin_precio(): void
    {
        $servicio = new ActualizarPreciosInsumosService();
        $insumo = new Insumo([
            'precio_costo' => null,
            'fecha_ultimo_precio' => '2024-03-12',
        ]);

        $aplicado = $servicio->aplicarAInsumo($insumo, 10, '2026-10-09');

        $this->assertFalse($aplicado);
        $this->assertNull($insumo->precio_costo);
        $this->assertSame('2024-03-12', $insumo->fecha_ultimo_precio?->toDateString());
    }

    #[Test]
    public function fecha_actualizacion_usa_la_fecha_recibida(): void
    {
        $servicio = new ActualizarPreciosInsumosService();

        $this->assertSame('2026-10-09', $servicio->fechaActualizacion('2026-10-09'));
    }
}
