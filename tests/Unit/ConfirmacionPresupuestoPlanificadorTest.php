<?php

namespace Tests\Unit;

use App\Services\ConfirmacionPresupuestoPlanificador;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ConfirmacionPresupuestoPlanificadorTest extends TestCase
{
    private ConfirmacionPresupuestoPlanificador $planificador;

    protected function setUp(): void
    {
        parent::setUp();

        $this->planificador = new ConfirmacionPresupuestoPlanificador;
    }

    #[Test]
    public function mobiliario_con_plantilla_y_faltante_genera_lote_sin_oc(): void
    {
        $plan = $this->planificador->planificar($this->snapshot([
            'mobiliarios' => [
                $this->mobiliario([
                    'cantidad_a_fabricar' => 4,
                    'plantilla'           => ['id' => 10, 'nombre' => 'Tapizado'],
                ]),
            ],
        ]));

        $this->assertCount(1, $plan['lotes']);
        $this->assertSame('mobiliario', $plan['lotes'][0]['entidad_tipo']);
        $this->assertSame(4, $plan['lotes'][0]['cantidad']);
        $this->assertSame('manual', $plan['lotes'][0]['origen_tipo']);
        $this->assertSame([], $plan['ordenes_compra']);
    }

    #[Test]
    public function mobiliario_con_plantilla_y_stock_cubierto_no_genera_lote(): void
    {
        $plan = $this->planificador->planificar($this->snapshot([
            'mobiliarios' => [
                $this->mobiliario([
                    'cantidad_a_fabricar' => 0,
                    'plantilla'           => ['id' => 10],
                ]),
            ],
        ]));

        $this->assertSame([], $plan['lotes']);
        $this->assertSame([], $plan['ordenes_compra']);
    }

    #[Test]
    public function insumo_con_plantilla_y_sin_stock_genera_lote_sin_oc(): void
    {
        $plan = $this->planificador->planificar($this->snapshot([
            'insumos' => [
                $this->insumo([
                    'demanda'          => 8,
                    'stock_disponible' => 0,
                    'plantilla'        => ['id' => 20, 'nombre' => 'Pintura'],
                ]),
            ],
        ]));

        $this->assertCount(1, $plan['lotes']);
        $this->assertSame('insumo', $plan['lotes'][0]['entidad_tipo']);
        $this->assertSame(8.0, $plan['lotes'][0]['cantidad']);
        $this->assertSame('manual', $plan['lotes'][0]['origen_tipo']);
        $this->assertSame([], $plan['ordenes_compra']);
    }

    #[Test]
    public function insumo_sin_plantilla_y_sin_stock_genera_oc_sin_lote(): void
    {
        $plan = $this->planificador->planificar($this->snapshot([
            'insumos' => [
                $this->insumo([
                    'demanda'          => 5,
                    'stock_disponible' => 0,
                    'proveedor_id'     => 3,
                    'plantilla'        => null,
                ]),
            ],
        ]));

        $this->assertSame([], $plan['lotes']);
        $this->assertCount(1, $plan['ordenes_compra']);
        $this->assertSame('crear', $plan['ordenes_compra'][0]['accion']);
        $this->assertSame(3, $plan['ordenes_compra'][0]['proveedor_id']);
        $this->assertSame('critica', $plan['ordenes_compra'][0]['prioridad']);
        $this->assertSame(5.0, $plan['ordenes_compra'][0]['items'][0]['cantidad']);
    }

    #[Test]
    public function insumo_con_stock_que_cubre_demanda_no_genera_nada(): void
    {
        $plan = $this->planificador->planificar($this->snapshot([
            'insumos' => [
                $this->insumo([
                    'demanda'          => 4,
                    'stock_disponible' => 10,
                    'plantilla'        => ['id' => 20],
                ]),
                $this->insumo([
                    'id'               => 11,
                    'demanda'          => 4,
                    'stock_disponible' => 10,
                    'plantilla'        => null,
                ]),
            ],
        ]));

        $this->assertSame([], $plan['lotes']);
        $this->assertSame([], $plan['ordenes_compra']);
    }

    #[Test]
    public function oc_pendiente_del_mismo_insumo_suma_cantidad(): void
    {
        $plan = $this->planificador->planificar($this->snapshot([
            'insumos' => [
                $this->insumo([
                    'id'               => 10,
                    'demanda'          => 6,
                    'stock_disponible' => 2,
                    'proveedor_id'     => 3,
                    'plantilla'        => null,
                ]),
            ],
            'ordenes_pendientes' => [
                [
                    'id'           => 50,
                    'proveedor_id' => 3,
                    'insumo_ids'   => [10],
                ],
            ],
        ]));

        $this->assertCount(1, $plan['ordenes_compra']);
        $this->assertSame('agregar', $plan['ordenes_compra'][0]['accion']);
        $this->assertSame(50, $plan['ordenes_compra'][0]['orden_compra_id']);
        $this->assertSame(4.0, $plan['ordenes_compra'][0]['items'][0]['cantidad']);
    }

    #[Test]
    public function oc_pendiente_de_otro_insumo_del_mismo_proveedor_crea_oc_nueva(): void
    {
        $plan = $this->planificador->planificar($this->snapshot([
            'insumos' => [
                $this->insumo([
                    'id'               => 10,
                    'demanda'          => 3,
                    'stock_disponible' => 0,
                    'proveedor_id'     => 3,
                    'plantilla'        => null,
                ]),
            ],
            'ordenes_pendientes' => [
                [
                    'id'           => 50,
                    'proveedor_id' => 3,
                    'insumo_ids'   => [99],
                ],
            ],
        ]));

        $this->assertCount(1, $plan['ordenes_compra']);
        $this->assertSame('crear', $plan['ordenes_compra'][0]['accion']);
        $this->assertNull($plan['ordenes_compra'][0]['orden_compra_id']);
    }

    #[Test]
    public function casco_usa_faltante_global_y_omite_lote_si_hay_cobertura(): void
    {
        $conFaltante = $this->planificador->planificar($this->snapshot([
            'mobiliarios' => [
                $this->mobiliario([
                    'es_casco'            => true,
                    'cantidad_a_fabricar' => 99,
                    'stock_casco'         => 2,
                    'demanda_activa'      => 10,
                    'lotes_abiertos'      => 3,
                    'plantilla'           => ['id' => 7],
                ]),
            ],
        ]));

        $this->assertCount(1, $conFaltante['lotes']);
        $this->assertSame(5, $conFaltante['lotes'][0]['cantidad']);
        $this->assertTrue($conFaltante['lotes'][0]['es_casco']);

        $cubierto = $this->planificador->planificar($this->snapshot([
            'mobiliarios' => [
                $this->mobiliario([
                    'es_casco'            => true,
                    'cantidad_a_fabricar' => 99,
                    'stock_casco'         => 5,
                    'demanda_activa'      => 5,
                    'lotes_abiertos'      => 0,
                    'plantilla'           => ['id' => 7],
                ]),
            ],
        ]));

        $this->assertSame([], $cubierto['lotes']);
    }

    #[Test]
    public function no_duplica_lote_si_ya_existe_para_el_presupuesto(): void
    {
        $plan = $this->planificador->planificar($this->snapshot([
            'mobiliarios' => [
                $this->mobiliario([
                    'cantidad_a_fabricar' => 4,
                    'ya_tiene_lote'       => true,
                    'plantilla'           => ['id' => 10],
                ]),
            ],
            'insumos' => [
                $this->insumo([
                    'demanda'          => 8,
                    'stock_disponible' => 0,
                    'ya_tiene_lote'    => true,
                    'plantilla'        => ['id' => 20],
                ]),
            ],
        ]));

        $this->assertSame([], $plan['lotes']);
    }

    #[Test]
    public function omitir_oc_no_impide_crear_lotes(): void
    {
        $plan = $this->planificador->planificar($this->snapshot([
            'omitir_oc' => true,
            'mobiliarios' => [
                $this->mobiliario([
                    'cantidad_a_fabricar' => 2,
                    'plantilla'           => ['id' => 10],
                ]),
            ],
            'insumos' => [
                $this->insumo([
                    'demanda'          => 5,
                    'stock_disponible' => 0,
                    'plantilla'        => null,
                ]),
            ],
        ]));

        $this->assertCount(1, $plan['lotes']);
        $this->assertSame([], $plan['ordenes_compra']);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function snapshot(array $overrides = []): array
    {
        return array_merge([
            'presupuesto' => [
                'id'      => 1,
                'codigo'  => 'PRES-TEST',
                'agencia' => 'Agencia',
                'marca'   => 'Marca',
            ],
            'omitir_oc'          => false,
            'mobiliarios'        => [],
            'insumos'            => [],
            'ordenes_pendientes' => [],
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function mobiliario(array $overrides = []): array
    {
        return array_merge([
            'id'                  => 5,
            'nombre'              => 'Sillón',
            'cantidad_a_fabricar' => 0,
            'es_casco'            => false,
            'ya_tiene_lote'       => false,
            'plantilla'           => null,
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function insumo(array $overrides = []): array
    {
        return array_merge([
            'id'               => 10,
            'nombre'           => 'Tela',
            'codigo'           => 'INS-0010',
            'demanda'          => 0,
            'stock_disponible' => 0,
            'proveedor_id'     => null,
            'precio_costo'     => 1.5,
            'ya_tiene_lote'    => false,
            'plantilla'        => null,
        ], $overrides);
    }
}
