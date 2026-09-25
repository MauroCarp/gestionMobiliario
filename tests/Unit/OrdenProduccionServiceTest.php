<?php

namespace Tests\Unit;

use App\Exceptions\OrdenProduccionException;
use App\Filament\Resources\OrdenProduccionResource;
use App\Models\Insumo;
use App\Models\LoteProcesoExterno;
use App\Models\Mobiliario;
use App\Models\OrdenCompra;
use App\Models\OrdenProduccion;
use App\Models\OrdenProduccionItem;
use App\Models\OrdenProduccionItemEtapa;
use App\Models\OrdenProduccionMovimiento;
use App\Models\ReservaStock;
use App\Services\OrdenProduccionService;
use App\Services\PresupuestoItemProduccionService;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Tests\OrdenProduccionIsolatedTestCase;

class OrdenProduccionServiceTest extends OrdenProduccionIsolatedTestCase
{
    private OrdenProduccionService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new OrdenProduccionService;
    }

    #[Test]
    public function inicia_congela_bom_y_reserva_insumos(): void
    {
        $marca = $this->crearMarca(['nombre' => 'Chery']);
        $mobiliario = $this->crearMobiliario(['nombre' => 'Mostrador', 'version_actual' => 2]);
        $this->asociarMarca($mobiliario, $marca);
        $melamina = $this->crearInsumo(['nombre' => 'Melamina', 'stock_actual' => 20]);
        $herraje = $this->crearInsumo(['nombre' => 'Herraje', 'stock_actual' => 10]);
        $this->agregarComposicion($mobiliario, $melamina, 3, casco: false);
        $this->agregarComposicion($mobiliario, $herraje, 1, casco: false);

        [$orden] = $this->crearOrdenConItem($mobiliario, $marca, 4);

        $iniciada = $this->service->iniciar($orden);

        $this->assertSame('en_proceso', $iniciada->estado);
        $this->assertNotNull($iniciada->iniciado_at);

        $item = $iniciada->items->first();
        $this->assertSame(2, $item->version_composicion);
        $this->assertCount(2, $item->insumos);
        $this->assertEquals(12.0, (float) $item->insumos->firstWhere('insumo_id', $melamina->id)->cantidad_total);
        $this->assertCount(count(PresupuestoItemProduccionService::ETAPAS_PREDETERMINADAS), $item->etapas);

        $this->assertSame(2, ReservaStock::query()->where('orden_produccion_id', $iniciada->id)->where('estado', 'activa')->count());
        $this->assertEquals(12.0, $melamina->fresh()->stock_reservado);
        $this->assertEquals(8.0, $melamina->fresh()->stock_disponible);
        $this->assertSame(0, OrdenCompra::query()->count());
        $this->assertSame(0, LoteProcesoExterno::query()->count());
    }

    #[Test]
    public function analiza_disponibilidad_permite_iniciar_aunque_haya_faltantes(): void
    {
        $marca = $this->crearMarca();
        $mobiliario = $this->crearMobiliario();
        $this->asociarMarca($mobiliario, $marca);
        $insumo = $this->crearInsumo(['stock_actual' => 0]);
        $this->agregarComposicion($mobiliario, $insumo, 2);

        [$orden] = $this->crearOrdenConItem($mobiliario, $marca, 1);
        $analisis = $this->service->analizarDisponibilidad($orden);

        $this->assertTrue($analisis['puede_iniciar']);
        $this->assertTrue($analisis['generara_reposicion']);
        $this->assertNotEmpty($analisis['faltantes']);
    }

    #[Test]
    public function inicia_con_faltantes_reserva_demanda_y_crea_oc(): void
    {
        $marca = $this->crearMarca();
        $mobiliario = $this->crearMobiliario();
        $this->asociarMarca($mobiliario, $marca);
        $insumo = $this->crearInsumo([
            'stock_actual' => 10,
            'proveedor_id' => 3,
            'precio_costo' => 15.5,
        ]);
        $this->agregarComposicion($mobiliario, $insumo, 2);

        ReservaStock::query()->create([
            'presupuesto_id' => 99,
            'insumo_id' => $insumo->id,
            'cantidad_reservada' => 5,
            'estado' => 'activa',
        ]);

        [$orden] = $this->crearOrdenConItem($mobiliario, $marca, 3, ['codigo' => 'OP-2026-0099']);

        $iniciada = $this->service->iniciar($orden);

        $this->assertSame('en_proceso', $iniciada->estado);
        $this->assertEquals(6.0, (float) ReservaStock::query()
            ->where('orden_produccion_id', $iniciada->id)
            ->where('estado', 'activa')
            ->value('cantidad_reservada'));
        $this->assertEquals(10.0, (float) $insumo->fresh()->stock_actual);

        $oc = OrdenCompra::query()->where('orden_produccion_id', $iniciada->id)->first();
        $this->assertNotNull($oc);
        $this->assertSame('sugerida', $oc->estado);
        $this->assertTrue($oc->generado_automaticamente);
        $this->assertSame(3, (int) $oc->proveedor_id);
        $this->assertStringContainsString('OP-2026-0099', (string) $oc->observaciones);
        $this->assertEquals(1.0, (float) $oc->items()->value('cantidad_solicitada'));
        $this->assertSame(0, LoteProcesoExterno::query()->count());
    }

    #[Test]
    public function inicia_con_faltantes_y_plantilla_crea_lote_sin_oc(): void
    {
        $marca = $this->crearMarca();
        $mobiliario = $this->crearMobiliario();
        $this->asociarMarca($mobiliario, $marca);
        $insumo = $this->crearInsumo(['stock_actual' => 2]);
        $this->agregarComposicion($mobiliario, $insumo, 5);
        $this->crearPlantillaInsumo($insumo);

        [$orden] = $this->crearOrdenConItem($mobiliario, $marca, 1);

        $iniciada = $this->service->iniciar($orden);

        $this->assertSame('en_proceso', $iniciada->estado);
        $this->assertEquals(5.0, (float) ReservaStock::query()
            ->where('orden_produccion_id', $iniciada->id)
            ->value('cantidad_reservada'));

        $lote = LoteProcesoExterno::query()
            ->where('origen_tipo', 'orden_produccion')
            ->where('origen_id', $iniciada->id)
            ->first();

        $this->assertNotNull($lote);
        $this->assertSame('insumo', $lote->entidad_tipo);
        $this->assertSame($insumo->id, (int) $lote->entidad_id);
        $this->assertEquals(3.0, (float) $lote->cantidad);
        $this->assertSame('pendiente', $lote->estado);
        $this->assertSame(0, OrdenCompra::query()->count());
    }

    #[Test]
    public function no_recrea_oc_automaticas_si_la_op_ya_tiene_alguna(): void
    {
        $marca = $this->crearMarca();
        $mobiliario = $this->crearMobiliario();
        $this->asociarMarca($mobiliario, $marca);
        $insumo = $this->crearInsumo(['stock_actual' => 0]);
        $this->agregarComposicion($mobiliario, $insumo, 2);

        [$orden] = $this->crearOrdenConItem($mobiliario, $marca, 1);

        OrdenCompra::query()->create([
            'estado' => 'sugerida',
            'prioridad' => 'alta',
            'generado_automaticamente' => true,
            'orden_produccion_id' => $orden->id,
            'observaciones' => 'Preexistente',
        ]);

        $this->service->iniciar($orden);

        $this->assertSame(1, OrdenCompra::query()->where('orden_produccion_id', $orden->id)->count());
    }

    #[Test]
    public function ingreso_parcial_consume_proporcional_e_incrementa_stock(): void
    {
        [$orden, $item, $insumo, $mobiliario] = $this->ordenIniciada(
            cantidad: 5,
            unitario: 2,
            stockInsumo: 20,
        );

        $this->service->registrarIngreso($item, 2, 'primer lote');

        $this->assertSame(2, $item->fresh()->cantidad_ingresada);
        $this->assertSame('en_proceso', $item->fresh()->estado);
        $this->assertSame('en_proceso', $orden->fresh()->estado);
        $this->assertSame(0, $mobiliario->fresh()->stock_actual);
        $this->assertEquals(16.0, (float) $insumo->fresh()->stock_actual);
        $this->assertEquals(6.0, (float) $insumo->fresh()->stock_reservado);
        $this->assertSame(1, OrdenProduccionMovimiento::count());

        $this->service->registrarIngreso($item->fresh(), 3);

        $this->assertSame(5, $item->fresh()->cantidad_ingresada);
        $this->assertSame('completada', $item->fresh()->estado);
        $this->assertSame('completada', $orden->fresh()->estado);
        $this->assertSame(0, $mobiliario->fresh()->stock_actual);
        $this->assertEquals(10.0, (float) $insumo->fresh()->stock_actual);
        $this->assertEquals(0.0, (float) $insumo->fresh()->stock_reservado);
        $this->assertSame('consumida', ReservaStock::query()->where('orden_produccion_id', $orden->id)->value('estado'));
    }

    #[Test]
    public function cancela_libera_reserva_pendiente_sin_revertir_ingresos(): void
    {
        [$orden, $item, $insumo, $mobiliario] = $this->ordenIniciada(
            cantidad: 4,
            unitario: 3,
            stockInsumo: 20,
        );

        $this->service->registrarIngreso($item, 1);

        $this->service->cancelar($orden->fresh(), 'ya no se necesita');

        $this->assertSame('cancelada', $orden->fresh()->estado);
        $this->assertSame(0, $mobiliario->fresh()->stock_actual);
        $this->assertEquals(17.0, (float) $insumo->fresh()->stock_actual);
        $this->assertSame('liberada', ReservaStock::query()->where('orden_produccion_id', $orden->id)->value('estado'));
        $this->assertEquals(0.0, (float) $insumo->fresh()->stock_reservado);
        $this->assertSame(1, $item->fresh()->cantidad_ingresada);
    }

    #[Test]
    public function cancela_libera_reserva_y_cancela_lote_abierto_sin_anular_oc(): void
    {
        $marca = $this->crearMarca();
        $mobiliario = $this->crearMobiliario();
        $this->asociarMarca($mobiliario, $marca);
        $insumoLote = $this->crearInsumo(['nombre' => 'Pintura', 'stock_actual' => 0]);
        $insumoOc = $this->crearInsumo(['nombre' => 'Tornillo', 'stock_actual' => 0]);
        $this->agregarComposicion($mobiliario, $insumoLote, 2);
        $this->agregarComposicion($mobiliario, $insumoOc, 3);
        $this->crearPlantillaInsumo($insumoLote);

        [$orden] = $this->crearOrdenConItem($mobiliario, $marca, 1);
        $iniciada = $this->service->iniciar($orden);

        $this->assertSame(1, LoteProcesoExterno::query()->where('origen_id', $iniciada->id)->count());
        $this->assertSame(1, OrdenCompra::query()->where('orden_produccion_id', $iniciada->id)->count());

        $this->service->cancelar($iniciada->fresh(), 'se aborta');

        $this->assertSame('cancelada', $iniciada->fresh()->estado);
        $this->assertSame('liberada', ReservaStock::query()
            ->where('orden_produccion_id', $iniciada->id)
            ->where('insumo_id', $insumoLote->id)
            ->value('estado'));
        $this->assertSame('cancelado', LoteProcesoExterno::query()
            ->where('origen_tipo', 'orden_produccion')
            ->where('origen_id', $iniciada->id)
            ->value('estado'));
        $this->assertSame('sugerida', OrdenCompra::query()
            ->where('orden_produccion_id', $iniciada->id)
            ->value('estado'));
    }

    #[Test]
    public function no_consume_componentes_de_casco_y_congela_bom_ante_cambios(): void
    {
        $marca = $this->crearMarca();
        $mobiliario = $this->crearMobiliario();
        $this->asociarMarca($mobiliario, $marca);
        $tela = $this->crearInsumo(['nombre' => 'Tela', 'stock_actual' => 50]);
        $casco = $this->crearInsumo(['nombre' => 'Espuma casco', 'stock_actual' => 50]);
        $this->agregarComposicion($mobiliario, $tela, 1);
        $this->agregarComposicion($mobiliario, $casco, 1, casco: true);

        [$orden, $item] = $this->crearOrdenConItem($mobiliario, $marca, 2);
        $this->service->iniciar($orden);

        $this->assertSame(1, $item->fresh()->insumos()->count());
        $this->assertSame($tela->id, $item->fresh()->insumos()->value('insumo_id'));
        $this->assertEquals(0.0, (float) $casco->fresh()->stock_reservado);

        $tela->composiciones()->update(['cantidad' => 9]);

        $this->service->registrarIngreso($item->fresh(), 1);

        $this->assertEquals(49.0, (float) $tela->fresh()->stock_actual);
        $this->assertEquals(50.0, (float) $casco->fresh()->stock_actual);
    }

    #[Test]
    public function permite_varias_marcas_y_comparte_insumo_entre_lineas(): void
    {
        $chery = $this->crearMarca(['nombre' => 'Chery']);
        $fiat = $this->crearMarca(['nombre' => 'Fiat']);
        $escritorio = $this->crearMobiliario(['nombre' => 'Escritorio']);
        $estanteria = $this->crearMobiliario(['nombre' => 'Estantería']);
        $this->asociarMarca($escritorio, $chery);
        $this->asociarMarca($estanteria, $fiat);

        $melamina = $this->crearInsumo(['stock_actual' => 30]);
        $this->agregarComposicion($escritorio, $melamina, 2);
        $this->agregarComposicion($estanteria, $melamina, 5);

        $orden = OrdenProduccion::query()->create(['estado' => 'borrador']);
        $lineaChery = OrdenProduccionItem::query()->create([
            'orden_produccion_id' => $orden->id,
            'marca_id' => $chery->id,
            'mobiliario_id' => $escritorio->id,
            'cantidad' => 3,
        ]);
        $lineaFiat = OrdenProduccionItem::query()->create([
            'orden_produccion_id' => $orden->id,
            'marca_id' => $fiat->id,
            'mobiliario_id' => $estanteria->id,
            'cantidad' => 2,
        ]);

        $this->service->iniciar($orden->fresh());

        $this->assertEquals(16.0, (float) $melamina->fresh()->stock_reservado);
        $this->assertSame(1, ReservaStock::query()->where('orden_produccion_id', $orden->id)->count());

        $this->service->registrarIngreso($lineaChery->fresh(), 3);
        $this->assertSame(0, $escritorio->fresh()->stock_actual);
        $this->assertSame(0, $estanteria->fresh()->stock_actual);
        $this->assertEquals(10.0, (float) $melamina->fresh()->stock_reservado);

        $this->service->registrarIngreso($lineaFiat->fresh(), 2);
        $this->assertSame(0, $estanteria->fresh()->stock_actual);
        $this->assertSame('completada', $orden->fresh()->estado);
        $this->assertEquals(0.0, (float) $melamina->fresh()->stock_reservado);
    }

    #[Test]
    public function protege_doble_inicio_exceso_de_ingreso_y_operaciones_invalidas(): void
    {
        [$orden, $item] = $this->ordenIniciada(cantidad: 2, unitario: 1, stockInsumo: 10);

        try {
            $this->service->iniciar($orden->fresh());
            $this->fail('No debió iniciar dos veces.');
        } catch (OrdenProduccionException $e) {
            $this->assertStringContainsString('borrador', $e->getMessage());
        }

        try {
            $this->service->registrarIngreso($item->fresh(), 0);
            $this->fail('No debió aceptar cantidad 0.');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('mayor a cero', $e->getMessage());
        }

        try {
            $this->service->registrarIngreso($item->fresh(), 3);
            $this->fail('No debió aceptar un excedente.');
        } catch (OrdenProduccionException $e) {
            $this->assertStringContainsString('pendiente', $e->getMessage());
        }

        $this->service->pausar($orden->fresh());

        try {
            $this->service->registrarIngreso($item->fresh(), 1);
            $this->fail('No debió ingresar stock con la orden pausada.');
        } catch (OrdenProduccionException $e) {
            $this->assertStringContainsString('en proceso', $e->getMessage());
        }

        $this->service->reanudar($orden->fresh());
        $this->service->registrarIngreso($item->fresh(), 2);
        $this->assertSame('completada', $orden->fresh()->estado);

        try {
            $this->service->cancelar($orden->fresh());
            $this->fail('No debió cancelar una orden completada.');
        } catch (OrdenProduccionException $e) {
            $this->assertStringContainsString('cancelar', $e->getMessage());
        }
    }

    #[Test]
    public function rechaza_marca_ajena_al_mobiliario(): void
    {
        $propia = $this->crearMarca(['nombre' => 'Propia']);
        $ajena = $this->crearMarca(['nombre' => 'Ajena']);
        $mobiliario = $this->crearMobiliario();
        $this->asociarMarca($mobiliario, $propia);
        $insumo = $this->crearInsumo(['stock_actual' => 10]);
        $this->agregarComposicion($mobiliario, $insumo, 1);

        [$orden] = $this->crearOrdenConItem($mobiliario, $ajena, 1);

        try {
            $this->service->iniciar($orden);
            $this->fail('Debió validar la marca del mobiliario.');
        } catch (OrdenProduccionException $e) {
            $this->assertStringContainsString('no pertenece', $e->getMessage());
        }
    }

    #[Test]
    public function actualiza_etapas_de_seguimiento(): void
    {
        [$orden, $item] = $this->ordenIniciada(cantidad: 1, unitario: 1, stockInsumo: 5);

        $etapas = $item->fresh()->etapas;
        $this->assertGreaterThan(0, $etapas->count());

        $this->service->actualizarEtapas($item->fresh(), $etapas->map(fn (OrdenProduccionItemEtapa $etapa): array => [
            'id' => $etapa->id,
            'estado' => $etapa->orden === 1 ? 'completado' : 'pendiente',
        ])->all());

        $this->assertSame('completado', $item->fresh()->etapas()->where('orden', 1)->value('estado'));
        $this->assertSame('en_proceso', $orden->fresh()->estado);
    }

    #[Test]
    public function reserva_y_consume_solo_insumos_seleccionados(): void
    {
        $marca = $this->crearMarca();
        $mobiliario = $this->crearMobiliario(['nombre' => 'Escritorio recto', 'stock_actual' => 7]);
        $this->asociarMarca($mobiliario, $marca);
        $cajonera = $this->crearInsumo(['nombre' => 'Cajonera', 'stock_actual' => 10]);
        $tablero = $this->crearInsumo(['nombre' => 'Tablero', 'stock_actual' => 10]);
        $this->agregarComposicion($mobiliario, $cajonera, 1);
        $this->agregarComposicion($mobiliario, $tablero, 1);

        [$orden, $item] = $this->crearOrdenConItem($mobiliario, $marca, 2, [], [
            'insumos_seleccionados' => [$cajonera->id],
        ]);

        $this->service->iniciar($orden);

        $this->assertSame(1, $item->fresh()->insumos()->count());
        $this->assertSame($cajonera->id, $item->fresh()->insumos()->value('insumo_id'));
        $this->assertEquals(2.0, (float) $cajonera->fresh()->stock_reservado);
        $this->assertEquals(0.0, (float) $tablero->fresh()->stock_reservado);

        $this->service->registrarIngreso($item->fresh(), 2);

        $this->assertEquals(8.0, (float) $cajonera->fresh()->stock_actual);
        $this->assertEquals(10.0, (float) $tablero->fresh()->stock_actual);
        $this->assertSame(7, $mobiliario->fresh()->stock_actual);
    }

    #[Test]
    public function rechaza_seleccion_vacia_o_ajena_cuando_hay_composicion(): void
    {
        $marca = $this->crearMarca();
        $mobiliario = $this->crearMobiliario();
        $this->asociarMarca($mobiliario, $marca);
        $insumo = $this->crearInsumo(['stock_actual' => 10]);
        $ajeno = $this->crearInsumo(['nombre' => 'Ajeno', 'stock_actual' => 10]);
        $this->agregarComposicion($mobiliario, $insumo, 1);

        [$vacia] = $this->crearOrdenConItem($mobiliario, $marca, 1, [], [
            'insumos_seleccionados' => [],
        ]);

        try {
            $this->service->iniciar($vacia);
            $this->fail('Debió exigir al menos un insumo.');
        } catch (OrdenProduccionException $e) {
            $this->assertStringContainsString('al menos un insumo', $e->getMessage());
        }

        [$ajena] = $this->crearOrdenConItem($mobiliario, $marca, 1, [], [
            'insumos_seleccionados' => [$ajeno->id],
        ]);

        try {
            $this->service->iniciar($ajena);
            $this->fail('Debió rechazar un insumo ajeno a la composición.');
        } catch (OrdenProduccionException $e) {
            $this->assertStringContainsString('no pertenecen', $e->getMessage());
        }
    }

    #[Test]
    public function el_checklist_ofrece_solo_mobiliarios_e_insumos_de_la_marca(): void
    {
        $chery = $this->crearMarca(['nombre' => 'Chery']);
        $fiat = $this->crearMarca(['nombre' => 'Fiat']);
        $escritorio = $this->crearMobiliario(['nombre' => 'Escritorio Chery']);
        $estanteria = $this->crearMobiliario(['nombre' => 'Estantería Fiat']);
        $this->asociarMarca($escritorio, $chery);
        $this->asociarMarca($estanteria, $fiat);
        $cajonera = $this->crearInsumo(['nombre' => 'Cajonera']);
        $this->agregarComposicion($escritorio, $cajonera, 1);

        $opcionesChery = Mobiliario::query()
            ->whereHas('marcas', fn ($query) => $query->where('marcas.id', $chery->id))
            ->pluck('id');

        $this->assertTrue($opcionesChery->contains($escritorio->id));
        $this->assertFalse($opcionesChery->contains($estanteria->id));

        $insumos = OrdenProduccionResource::opcionesInsumosFabricables($escritorio->id);
        $this->assertArrayHasKey($cajonera->id, $insumos);
        $this->assertSame([], OrdenProduccionResource::opcionesInsumosFabricables($estanteria->id));
    }

    /**
     * @return array{0: OrdenProduccion, 1: OrdenProduccionItem, 2: Insumo, 3: Mobiliario}
     */
    private function ordenIniciada(int $cantidad, float $unitario, float $stockInsumo): array
    {
        $marca = $this->crearMarca();
        $mobiliario = $this->crearMobiliario();
        $this->asociarMarca($mobiliario, $marca);
        $insumo = $this->crearInsumo(['stock_actual' => $stockInsumo]);
        $this->agregarComposicion($mobiliario, $insumo, $unitario);

        [$orden, $item] = $this->crearOrdenConItem($mobiliario, $marca, $cantidad);
        $iniciada = $this->service->iniciar($orden);

        return [$iniciada, $item->fresh(), $insumo->fresh(), $mobiliario->fresh()];
    }
}
