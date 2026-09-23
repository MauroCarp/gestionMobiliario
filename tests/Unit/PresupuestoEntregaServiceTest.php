<?php

namespace Tests\Unit;

use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Models\PresupuestoItemEntrega;
use App\Services\PresupuestoEntregaService;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Tests\IsolatedSqliteTestCase;

class PresupuestoEntregaServiceTest extends IsolatedSqliteTestCase
{
    private PresupuestoEntregaService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new PresupuestoEntregaService();
    }

    #[Test]
    public function registra_entregas_sucesivas_hasta_completar_el_item(): void
    {
        $presupuesto = $this->presupuesto();
        $item = $this->item($presupuesto, ['cantidad' => 10]);

        $this->service->registrarEntregaItem($item, 4);
        $item->refresh();

        $this->assertSame(4, $item->cantidad_entregada);
        $this->assertSame(6, $item->cantidadPendiente());
        $this->assertSame('entregado_parcial', $presupuesto->fresh()->estado);
        $this->assertSame(1, $item->entregas()->activas()->count());

        $this->service->registrarEntregaItem($item->fresh(), 3);
        $this->service->registrarEntregaItem($item->fresh(), 3);
        $item->refresh();

        $this->assertSame(10, $item->cantidad_entregada);
        $this->assertTrue($item->estaEntregado());
        $this->assertSame('entregado', $presupuesto->fresh()->estado);
        $this->assertSame(3, $item->entregas()->activas()->count());
    }

    #[Test]
    public function rechaza_cantidad_cero_mayor_al_pendiente_y_lote_sin_finalizar(): void
    {
        $presupuesto = $this->presupuesto();
        $item = $this->item($presupuesto, ['cantidad' => 5]);

        try {
            $this->service->registrarEntregaItem($item, 0);
            $this->fail('Debió rechazar cantidad cero.');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('mayor a cero', $e->getMessage());
        }

        try {
            $this->service->registrarEntregaItem($item->fresh(), 6);
            $this->fail('Debió rechazar un excedente.');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('supera la cantidad pendiente', $e->getMessage());
        }

        $lote = $this->item($presupuesto, [
            'cantidad' => 4,
            'insumo_id' => 9,
        ]);

        try {
            $this->service->registrarEntregaItem($lote, 2);
            $this->fail('Debió exigir finalización del lote.');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('finalizado', $e->getMessage());
        }

        $this->assertSame(0, PresupuestoItemEntrega::count());
    }

    #[Test]
    public function entrega_completa_registra_solo_el_saldo_pendiente(): void
    {
        $presupuesto = $this->presupuesto();
        $itemA = $this->item($presupuesto, ['cantidad' => 10, 'orden' => 1]);
        $itemB = $this->item($presupuesto, ['cantidad' => 2, 'orden' => 2]);

        $this->service->registrarEntregaItem($itemA, 4);
        $this->service->marcarEntregaCompleta($presupuesto->fresh());

        $this->assertSame(10, $itemA->fresh()->cantidad_entregada);
        $this->assertSame(2, $itemB->fresh()->cantidad_entregada);
        $this->assertSame('entregado', $presupuesto->fresh()->estado);
        $this->assertSame(3, PresupuestoItemEntrega::count());
    }

    #[Test]
    public function entrega_completa_falla_si_hay_lotes_sin_finalizar(): void
    {
        $presupuesto = $this->presupuesto();
        $this->item($presupuesto, ['cantidad' => 3]);
        $this->item($presupuesto, ['cantidad' => 2, 'insumo_id' => 3]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('finalizar la producción');

        $this->service->marcarEntregaCompleta($presupuesto);
    }

    #[Test]
    public function entrega_parcial_por_cantidades_y_anula_con_motivo(): void
    {
        $presupuesto = $this->presupuesto();
        $item = $this->item($presupuesto, ['cantidad' => 10]);

        $this->service->marcarEntregaParcial($presupuesto, [$item->id => 4]);
        $item->refresh();

        $this->assertSame(4, $item->cantidad_entregada);
        $this->assertSame('entregado_parcial', $presupuesto->fresh()->estado);

        $movimiento = $item->entregas()->activas()->first();
        $this->assertNotNull($movimiento);

        $this->service->anularEntrega($movimiento, 'Se devolvió mercadería');
        $item->refresh();

        $this->assertSame(0, $item->cantidad_entregada);
        $this->assertNull($item->entregado_at);
        $this->assertTrue($movimiento->fresh()->estaAnulada());
        $this->assertSame('Se devolvió mercadería', $movimiento->fresh()->motivo_anulacion);
        $this->assertSame('confirmado', $presupuesto->fresh()->estado);
    }

    #[Test]
    public function anular_exige_motivo_y_no_borra_el_movimiento(): void
    {
        $presupuesto = $this->presupuesto();
        $item = $this->item($presupuesto, ['cantidad' => 6]);
        $this->service->registrarEntregaItem($item, 2);
        $movimiento = $item->entregas()->activas()->firstOrFail();

        try {
            $this->service->anularEntrega($movimiento, '   ');
            $this->fail('Debió exigir motivo.');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('motivo', $e->getMessage());
        }

        $this->assertSame(1, PresupuestoItemEntrega::count());
        $this->assertFalse($movimiento->fresh()->estaAnulada());
    }

    #[Test]
    public function entrega_parcial_vacia_es_invalida(): void
    {
        $presupuesto = $this->presupuesto();
        $this->item($presupuesto, ['cantidad' => 5]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('al menos una cantidad');

        $this->service->marcarEntregaParcial($presupuesto, [1 => 0, 2 => null]);
    }

    private function presupuesto(string $estado = 'confirmado'): Presupuesto
    {
        return Presupuesto::query()->create([
            'codigo'        => 'PRES-TEST-'.uniqid(),
            'estado'        => $estado,
            'version'       => 1,
            'fecha_emision' => now()->toDateString(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $atributos
     */
    private function item(Presupuesto $presupuesto, array $atributos = []): PresupuestoItem
    {
        return $presupuesto->items()->create(array_merge([
            'cantidad'           => 1,
            'cantidad_entregada' => 0,
            'orden'              => 0,
        ], $atributos));
    }
}
