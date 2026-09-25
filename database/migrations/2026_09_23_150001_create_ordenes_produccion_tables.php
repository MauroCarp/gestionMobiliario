<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_produccion', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('codigo')->unique();
            $table->string('estado')->default('borrador');
            $table->string('prioridad')->default('media');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_compromiso')->nullable();
            $table->timestamp('iniciado_at')->nullable();
            $table->unsignedBigInteger('iniciado_por')->nullable();
            $table->timestamp('completado_at')->nullable();
            $table->timestamp('cancelado_at')->nullable();
            $table->unsignedBigInteger('cancelado_por')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('estado');
            $table->index('fecha_compromiso');
        });

        Schema::create('orden_produccion_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
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
            $table->timestamps();

            $table->index(['orden_produccion_id', 'estado']);
            $table->index(['mobiliario_id', 'marca_id']);
        });

        Schema::create('orden_produccion_item_insumos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('orden_produccion_item_id');
            $table->unsignedBigInteger('insumo_id');
            $table->decimal('cantidad_unitaria', 12, 4);
            $table->decimal('cantidad_total', 12, 4);
            $table->decimal('cantidad_consumida', 12, 4)->default(0);
            $table->timestamps();

            $table->unique(['orden_produccion_item_id', 'insumo_id'], 'op_item_insumo_unique');
        });

        Schema::create('orden_produccion_item_etapas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
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

            $table->unique(['orden_produccion_item_id', 'orden'], 'op_item_etapa_unique');
        });

        Schema::create('orden_produccion_movimientos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('orden_produccion_item_id');
            $table->unsignedInteger('cantidad');
            $table->timestamp('registrado_at');
            $table->unsignedBigInteger('registrado_por')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('orden_produccion_item_id');
        });

        Schema::create('orden_produccion_historial', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('orden_produccion_id');
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo');
            $table->text('comentario')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index('orden_produccion_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_produccion_historial');
        Schema::dropIfExists('orden_produccion_movimientos');
        Schema::dropIfExists('orden_produccion_item_etapas');
        Schema::dropIfExists('orden_produccion_item_insumos');
        Schema::dropIfExists('orden_produccion_items');
        Schema::dropIfExists('ordenes_produccion');
    }
};
