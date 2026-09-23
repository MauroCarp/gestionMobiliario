<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class IsolatedSqliteTestCase extends TestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__).'/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'                  => 'sqlite',
            'database'                => ':memory:',
            'prefix'                  => '',
            'foreign_key_constraints' => false,
        ]);
        $app['config']->set('activitylog.enabled', false);

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'activitylog.enabled' => false,
        ]);

        DB::purge();
        DB::setDefaultConnection('sqlite');

        $this->assertIsolatedSqlite();
        $this->disableModelSideEffects();
        $this->crearEsquema();
    }

    protected function assertIsolatedSqlite(): void
    {
        if (config('database.default') !== 'sqlite') {
            $this->fail('La prueba se abortó porque no está usando SQLite aislado.');
        }

        if (config('database.connections.sqlite.database') !== ':memory:') {
            $this->fail('La prueba se abortó porque SQLite no está en memoria.');
        }

        if (DB::getDriverName() !== 'sqlite') {
            $this->fail('La prueba se abortó porque la conexión activa no es SQLite.');
        }
    }

    protected function disableModelSideEffects(): void
    {
        \App\Models\Presupuesto::unsetEventDispatcher();
        \App\Models\PresupuestoItem::unsetEventDispatcher();
    }

    protected function crearEsquema(): void
    {
        Schema::create('presupuestos', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo')->nullable();
            $table->unsignedBigInteger('agencia_id')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->string('estado')->default('borrador');
            $table->unsignedInteger('version')->default(1);
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->text('metodo_pago')->nullable();
            $table->boolean('logistica_instalacion_propia')->default(false);
            $table->string('logistica_leyenda')->nullable();
            $table->decimal('logistica_costo', 12, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->text('notas_internas')->nullable();
            $table->unsignedBigInteger('aprobado_por')->nullable();
            $table->timestamp('aprobado_at')->nullable();
            $table->json('datos_adicionales')->nullable();
            $table->timestamps();
        });

        Schema::create('presupuesto_items', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('presupuesto_id');
            $table->unsignedBigInteger('mobiliario_id')->nullable();
            $table->unsignedBigInteger('insumo_id')->nullable();
            $table->unsignedInteger('cantidad')->default(1);
            $table->unsignedInteger('cantidad_entregada')->default(0);
            $table->unsignedInteger('cantidad_desde_stock')->nullable();
            $table->unsignedInteger('cantidad_a_fabricar')->nullable();
            $table->timestamp('stock_descontado_at')->nullable();
            $table->decimal('precio_unitario', 12, 2)->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamp('finalizado_at')->nullable();
            $table->unsignedBigInteger('finalizado_por')->nullable();
            $table->timestamp('entregado_at')->nullable();
            $table->unsignedBigInteger('entregado_por')->nullable();
            $table->text('entrega_observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('presupuesto_item_entregas', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('presupuesto_item_id');
            $table->unsignedInteger('cantidad');
            $table->timestamp('entregado_at');
            $table->unsignedBigInteger('entregado_por')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamp('anulado_at')->nullable();
            $table->unsignedBigInteger('anulado_por')->nullable();
            $table->text('motivo_anulacion')->nullable();
            $table->timestamps();
        });

        Schema::create('presupuesto_historial', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('presupuesto_id');
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo');
            $table->text('comentario')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        if ($this->app) {
            \App\Models\Presupuesto::setEventDispatcher($this->app['events']);
            \App\Models\PresupuestoItem::setEventDispatcher($this->app['events']);
        }

        parent::tearDown();
    }
}
