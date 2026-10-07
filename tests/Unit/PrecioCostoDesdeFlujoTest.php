<?php

namespace Tests\Unit;

use App\Models\Insumo;
use App\Models\PlantillaEtapa;
use App\Models\PlantillaFlujoExterno;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\IsolatedSqliteTestCase;

class PrecioCostoDesdeFlujoTest extends IsolatedSqliteTestCase
{
    protected function disableModelSideEffects(): void
    {
        parent::disableModelSideEffects();

        Insumo::setEventDispatcher($this->app['events']);
    }

    protected function crearEsquema(): void
    {
        parent::crearEsquema();

        Schema::create('insumos', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('nombre');
            $table->decimal('precio_costo', 12, 2)->nullable();
            $table->date('fecha_ultimo_precio')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('plantillas_flujo_externo', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre');
            $table->string('entidad_tipo');
            $table->unsignedBigInteger('entidad_id');
            $table->boolean('activo')->default(true);
            $table->integer('stock_casco')->default(0);
            $table->timestamps();
        });

        Schema::create('plantilla_etapas', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('plantilla_id');
            $table->unsignedTinyInteger('orden')->default(1);
            $table->unsignedBigInteger('tipo_proceso_id')->nullable();
            $table->unsignedBigInteger('tercero_id')->nullable();
            $table->unsignedSmallInteger('dias_estimados')->nullable();
            $table->decimal('ultimo_precio', 10, 2)->nullable();
            $table->date('fecha_ultimo_precio')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    #[Test]
    public function cambiar_ultimo_precio_actualiza_precio_costo_con_la_suma(): void
    {
        $insumo = $this->crearInsumo(['precio_costo' => 1, 'fecha_ultimo_precio' => '2026-01-15']);
        $plantilla = $this->crearPlantilla($insumo);
        $etapaA = $this->crearEtapa($plantilla, ['ultimo_precio' => 10]);
        $this->crearEtapa($plantilla, ['ultimo_precio' => 5, 'orden' => 2]);

        $this->assertSame(15.0, $insumo->fresh()->precio_costo);

        $etapaA->update(['ultimo_precio' => 20]);

        $insumo = $insumo->fresh();
        $this->assertSame(25.0, $insumo->precio_costo);
        $this->assertSame('2026-01-15', $insumo->fecha_ultimo_precio?->toDateString());
    }

    #[Test]
    public function agregar_o_borrar_una_etapa_con_precio_recalcula(): void
    {
        $insumo = $this->crearInsumo(['precio_costo' => 0]);
        $plantilla = $this->crearPlantilla($insumo);
        $this->crearEtapa($plantilla, ['ultimo_precio' => 10]);

        $this->assertSame(10.0, $insumo->fresh()->precio_costo);

        $etapaExtra = $this->crearEtapa($plantilla, ['ultimo_precio' => 4.5, 'orden' => 2]);

        $this->assertSame(14.5, $insumo->fresh()->precio_costo);

        $etapaExtra->delete();

        $this->assertSame(10.0, $insumo->fresh()->precio_costo);
    }

    #[Test]
    public function sin_ultimo_precio_en_etapas_no_cambia_el_precio_del_insumo(): void
    {
        $insumo = $this->crearInsumo(['precio_costo' => 99]);
        $plantilla = $this->crearPlantilla($insumo);
        $etapa = $this->crearEtapa($plantilla, ['ultimo_precio' => null]);

        $this->assertSame(99.0, $insumo->fresh()->precio_costo);

        $etapa->update(['observaciones' => 'sin precio']);

        $this->assertSame(99.0, $insumo->fresh()->precio_costo);
    }

    #[Test]
    public function con_dos_plantillas_no_cambia_el_precio(): void
    {
        $insumo = $this->crearInsumo(['precio_costo' => 50]);
        $plantillaA = $this->crearPlantilla($insumo, ['nombre' => 'Corte']);
        $plantillaB = $this->crearPlantilla($insumo, ['nombre' => 'Pintura']);
        $etapa = $this->crearEtapa($plantillaA, ['ultimo_precio' => 10]);
        $this->crearEtapa($plantillaB, ['ultimo_precio' => 20]);

        $this->assertSame(50.0, $insumo->fresh()->precio_costo);

        $etapa->update(['ultimo_precio' => 30]);

        $this->assertSame(50.0, $insumo->fresh()->precio_costo);
    }

    #[Test]
    public function al_borrar_una_de_dos_plantillas_actualiza_con_la_que_queda(): void
    {
        $insumo = $this->crearInsumo(['precio_costo' => 0]);
        $plantillaA = $this->crearPlantilla($insumo, ['nombre' => 'Corte']);
        $this->crearEtapa($plantillaA, ['ultimo_precio' => 10]);

        $this->assertSame(10.0, $insumo->fresh()->precio_costo);

        $plantillaB = $this->crearPlantilla($insumo, ['nombre' => 'Pintura']);
        $this->crearEtapa($plantillaB, ['ultimo_precio' => 20]);
        $this->crearEtapa($plantillaA, ['ultimo_precio' => 15, 'orden' => 2]);

        $this->assertSame(10.0, $insumo->fresh()->precio_costo);

        $plantillaB->delete();

        $this->assertSame(25.0, $insumo->fresh()->precio_costo);
    }

    #[Test]
    public function plantilla_de_mobiliario_no_altera_insumos(): void
    {
        $insumo = $this->crearInsumo(['precio_costo' => 80]);
        $plantilla = PlantillaFlujoExterno::query()->create([
            'nombre' => 'Flujo silla',
            'entidad_tipo' => 'mobiliario',
            'entidad_id' => $insumo->id,
            'activo' => true,
            'stock_casco' => 0,
        ]);
        $this->crearEtapa($plantilla, ['ultimo_precio' => 1000]);

        $this->assertSame(80.0, $insumo->fresh()->precio_costo);
    }

    #[Test]
    public function reasignar_plantilla_actualiza_el_insumo_destino(): void
    {
        $origen = $this->crearInsumo(['nombre' => 'Origen', 'precio_costo' => 0]);
        $destino = $this->crearInsumo(['nombre' => 'Destino', 'precio_costo' => 50]);
        $plantilla = $this->crearPlantilla($origen);
        $this->crearEtapa($plantilla, ['ultimo_precio' => 12]);

        $this->assertSame(12.0, $origen->fresh()->precio_costo);

        $plantilla->update(['entidad_id' => $destino->id]);

        $this->assertSame(12.0, $origen->fresh()->precio_costo);
        $this->assertSame(12.0, $destino->fresh()->precio_costo);
    }

    private function crearInsumo(array $attrs = []): Insumo
    {
        return Insumo::query()->create(array_merge([
            'nombre' => 'Pata',
            'activo' => true,
        ], $attrs));
    }

    private function crearPlantilla(Insumo $insumo, array $attrs = []): PlantillaFlujoExterno
    {
        return PlantillaFlujoExterno::query()->create(array_merge([
            'nombre' => 'Flujo '.$insumo->nombre,
            'entidad_tipo' => 'insumo',
            'entidad_id' => $insumo->id,
            'activo' => true,
            'stock_casco' => 0,
        ], $attrs));
    }

    private function crearEtapa(PlantillaFlujoExterno $plantilla, array $attrs = []): PlantillaEtapa
    {
        return PlantillaEtapa::query()->create(array_merge([
            'plantilla_id' => $plantilla->id,
            'orden' => 1,
            'dias_estimados' => 1,
        ], $attrs));
    }
}
