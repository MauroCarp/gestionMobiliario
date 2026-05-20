<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_compra', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('codigo')->unique();
            $table->enum('estado', ['sugerida', 'pendiente', 'aprobada', 'recibida', 'cancelada'])->default('sugerida');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->boolean('generado_automaticamente')->default(true);
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('presupuesto_id')->nullable();
            $table->timestamps();

            $table->index('estado');
            $table->index('prioridad');
        });

        Schema::create('orden_compra_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('orden_compra_id');
            $table->unsignedBigInteger('insumo_id');
            $table->decimal('cantidad_solicitada', 10, 2);
            $table->decimal('cantidad_recibida', 10, 2)->default(0);
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_compra_items');
        Schema::dropIfExists('ordenes_compra');
    }
};
