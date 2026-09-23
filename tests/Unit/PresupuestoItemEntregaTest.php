<?php

namespace Tests\Unit;

use App\Filament\Forms\PresupuestoEntregaParcialForm;
use App\Models\PresupuestoItem;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PresupuestoItemEntregaTest extends TestCase
{
    #[Test]
    public function calcula_pendiente_y_estados_de_entrega(): void
    {
        $item = new PresupuestoItem([
            'cantidad' => 10,
            'cantidad_entregada' => 0,
        ]);

        $this->assertSame(10, $item->cantidadPendiente());
        $this->assertFalse($item->estaEntregado());
        $this->assertFalse($item->estaEntregaParcial());
        $this->assertSame('Pendiente', $item->estado_entrega);

        $item->cantidad_entregada = 4;
        $this->assertSame(6, $item->cantidadPendiente());
        $this->assertTrue($item->estaEntregaParcial());
        $this->assertSame('Parcial', $item->estado_entrega);

        $item->cantidad_entregada = 10;
        $this->assertSame(0, $item->cantidadPendiente());
        $this->assertTrue($item->estaEntregado());
        $this->assertFalse($item->estaEntregaParcial());
        $this->assertSame('Entregado', $item->estado_entrega);
    }

    #[Test]
    public function no_permite_entregar_mas_que_el_pendiente_ni_lotes_sin_finalizar(): void
    {
        $disponible = new PresupuestoItem([
            'cantidad' => 8,
            'cantidad_entregada' => 3,
        ]);

        $this->assertTrue($disponible->puedeRecibirEntrega());

        $fabricacionPendiente = new PresupuestoItem([
            'cantidad' => 8,
            'cantidad_entregada' => 0,
            'insumo_id' => 15,
        ]);

        $this->assertTrue($fabricacionPendiente->requiereFabricacion());
        $this->assertFalse($fabricacionPendiente->puedeRecibirEntrega());

        $fabricacionFinalizada = new PresupuestoItem([
            'cantidad' => 8,
            'cantidad_entregada' => 2,
            'insumo_id' => 15,
            'finalizado_at' => now(),
        ]);

        $this->assertTrue($fabricacionFinalizada->puedeRecibirEntrega());
        $this->assertSame(6, $fabricacionFinalizada->cantidadPendiente());
    }

    #[Test]
    public function el_formulario_parcial_omite_cantidades_nulas_o_cero(): void
    {
        $cantidades = PresupuestoEntregaParcialForm::cantidades([
            'items' => [
                ['id' => 1, 'cantidad_a_entregar' => 4],
                ['id' => 2, 'cantidad_a_entregar' => 0],
                ['id' => 3, 'cantidad_a_entregar' => null],
                ['id' => 0, 'cantidad_a_entregar' => 2],
            ],
        ]);

        $this->assertSame([1 => 4], $cantidades);
    }
}
