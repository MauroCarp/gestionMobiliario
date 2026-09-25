<?php

namespace Tests;

use App\Models\ComposicionTecnica;
use App\Models\Insumo;
use App\Models\Marca;
use App\Models\Mobiliario;
use App\Models\OrdenProduccion;
use App\Models\OrdenProduccionItem;
use App\Models\PlantillaFlujoExterno;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

abstract class OrdenProduccionIsolatedTestCase extends IsolatedSqliteTestCase
{
    protected function disableModelSideEffects(): void
    {
        parent::disableModelSideEffects();

        Mobiliario::unsetEventDispatcher();
    }

    protected function crearEsquema(): void
    {
        parent::crearEsquema();

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('marcas', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mobiliarios', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo_interno')->nullable();
            $table->string('nombre');
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->string('estado')->default('activo');
            $table->integer('stock_actual')->default(0);
            $table->unsignedInteger('version_actual')->default(1);
            $table->decimal('precio', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('marca_mobiliario', function (Blueprint $table): void {
            $table->unsignedBigInteger('mobiliario_id');
            $table->unsignedBigInteger('marca_id');
        });

        Schema::create('insumos', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('nombre');
            $table->decimal('stock_actual', 12, 4)->default(0);
            $table->decimal('stock_minimo', 12, 4)->nullable();
            $table->decimal('precio_costo', 12, 2)->nullable();
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('composicion_tecnica', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('mobiliario_id');
            $table->unsignedBigInteger('insumo_id');
            $table->decimal('cantidad', 12, 4);
            $table->unsignedInteger('version')->default(1);
            $table->boolean('activo')->default(true);
            $table->boolean('es_componente_casco')->default(false);
            $table->timestamps();
        });

        Schema::create('reservas_stock', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('presupuesto_id')->nullable();
            $table->unsignedBigInteger('orden_produccion_id')->nullable();
            $table->unsignedBigInteger('insumo_id');
            $table->decimal('cantidad_reservada', 12, 4);
            $table->string('estado')->default('activa');
            $table->timestamps();
        });

        Schema::create('ordenes_produccion', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('estado')->default('borrador');
            $table->date('fecha_inicio')->nullable();
            $table->timestamp('iniciado_at')->nullable();
            $table->unsignedBigInteger('iniciado_por')->nullable();
            $table->timestamp('completado_at')->nullable();
            $table->timestamp('cancelado_at')->nullable();
            $table->unsignedBigInteger('cancelado_por')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('orden_produccion_items', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('orden_produccion_id');
            $table->unsignedBigInteger('marca_id');
            $table->unsignedBigInteger('mobiliario_id');
            $table->unsignedInteger('cantidad');
            $table->unsignedInteger('cantidad_ingresada')->default(0);
            $table->string('estado')->default('pendiente');
            $table->unsignedInteger('version_composicion')->nullable();
            $table->timestamp('finalizado_at')->nullable();
            $table->text('observaciones')->nullable();
            $table->json('insumos_seleccionados')->nullable();
            $table->timestamps();
        });

        Schema::create('orden_produccion_item_insumos', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('orden_produccion_item_id');
            $table->unsignedBigInteger('insumo_id');
            $table->decimal('cantidad_unitaria', 12, 4);
            $table->decimal('cantidad_total', 12, 4);
            $table->decimal('cantidad_consumida', 12, 4)->default(0);
            $table->timestamps();
        });

        Schema::create('orden_produccion_item_etapas', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('orden_produccion_item_id');
            $table->string('nombre');
            $table->unsignedTinyInteger('orden');
            $table->string('estado')->default('pendiente');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->unsignedBigInteger('iniciado_por')->nullable();
            $table->unsignedBigInteger('completado_por')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('orden_produccion_movimientos', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('orden_produccion_item_id');
            $table->unsignedInteger('cantidad');
            $table->timestamp('registrado_at');
            $table->unsignedBigInteger('registrado_por')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('orden_produccion_historial', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('orden_produccion_id');
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo');
            $table->text('comentario')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('ordenes_compra', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('estado')->default('sugerida');
            $table->string('prioridad')->default('media');
            $table->boolean('generado_automaticamente')->default(true);
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('presupuesto_id')->nullable();
            $table->unsignedBigInteger('orden_produccion_id')->nullable();
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->date('fecha_pactada_entrega')->nullable();
            $table->timestamps();
        });

        Schema::create('orden_compra_items', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('orden_compra_id');
            $table->unsignedBigInteger('insumo_id');
            $table->decimal('cantidad_solicitada', 12, 4);
            $table->decimal('cantidad_recibida', 12, 4)->default(0);
            $table->decimal('precio_unitario', 12, 2)->nullable();
            $table->text('observaciones')->nullable();
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
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('lotes_proceso_externo', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo')->nullable();
            $table->unsignedBigInteger('plantilla_id')->nullable();
            $table->string('entidad_tipo');
            $table->unsignedBigInteger('entidad_id');
            $table->decimal('cantidad', 12, 4);
            $table->string('origen_tipo')->default('manual');
            $table->unsignedBigInteger('origen_id')->nullable();
            $table->string('estado')->default('pendiente');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_finalizacion_estimada')->nullable();
            $table->date('fecha_finalizacion_real')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lote_etapas', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('lote_id');
            $table->unsignedTinyInteger('orden')->default(1);
            $table->unsignedBigInteger('tipo_proceso_id')->nullable();
            $table->unsignedBigInteger('tercero_id')->nullable();
            $table->string('estado')->default('pendiente');
            $table->date('fecha_envio')->nullable();
            $table->date('fecha_recepcion_estimada')->nullable();
            $table->date('fecha_recepcion_real')->nullable();
            $table->decimal('costo', 10, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->timestamps();
        });
    }

    protected function crearPlantillaInsumo(Insumo $insumo, array $attrs = []): PlantillaFlujoExterno
    {
        return PlantillaFlujoExterno::query()->create(array_merge([
            'nombre' => 'Proceso '.$insumo->nombre,
            'entidad_tipo' => 'insumo',
            'entidad_id' => $insumo->id,
            'activo' => true,
            'stock_casco' => 0,
        ], $attrs));
    }

    protected function tearDown(): void
    {
        if ($this->app) {
            Mobiliario::setEventDispatcher($this->app['events']);
        }

        parent::tearDown();
    }

    protected function crearMarca(array $attrs = []): Marca
    {
        return Marca::query()->create(array_merge([
            'nombre' => 'Marca '.uniqid(),
            'activo' => true,
        ], $attrs));
    }

    protected function crearMobiliario(array $attrs = []): Mobiliario
    {
        return Mobiliario::query()->create(array_merge([
            'codigo_interno' => 'MOB-'.uniqid(),
            'nombre' => 'Escritorio',
            'estado' => 'activo',
            'stock_actual' => 0,
            'version_actual' => 1,
        ], $attrs));
    }

    protected function crearInsumo(array $attrs = []): Insumo
    {
        return Insumo::query()->create(array_merge([
            'nombre' => 'Melamina',
            'stock_actual' => 0,
            'activo' => true,
        ], $attrs));
    }

    protected function asociarMarca(Mobiliario $mobiliario, Marca $marca): void
    {
        $mobiliario->marcas()->syncWithoutDetaching([$marca->id]);
    }

    protected function agregarComposicion(
        Mobiliario $mobiliario,
        Insumo $insumo,
        float $cantidad,
        bool $casco = false,
    ): ComposicionTecnica {
        return ComposicionTecnica::query()->create([
            'mobiliario_id' => $mobiliario->id,
            'insumo_id' => $insumo->id,
            'cantidad' => $cantidad,
            'version' => $mobiliario->version_actual ?? 1,
            'activo' => true,
            'es_componente_casco' => $casco,
        ]);
    }

    protected function crearOrdenConItem(
        Mobiliario $mobiliario,
        Marca $marca,
        int $cantidad = 1,
        array $ordenAttrs = [],
        array $itemAttrs = [],
    ): array {
        $orden = OrdenProduccion::query()->create(array_merge([
            'estado' => 'borrador',
        ], $ordenAttrs));

        $item = OrdenProduccionItem::query()->create(array_merge([
            'orden_produccion_id' => $orden->id,
            'marca_id' => $marca->id,
            'mobiliario_id' => $mobiliario->id,
            'cantidad' => $cantidad,
            'cantidad_ingresada' => 0,
            'estado' => 'pendiente',
        ], $itemAttrs));

        return [$orden->fresh('items'), $item->fresh()];
    }
}
