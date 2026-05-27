<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes_proceso_externo', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('plantilla_id')->nullable()->constrained('plantillas_flujo_externo')->nullOnDelete();
            $table->enum('entidad_tipo', ['insumo', 'mobiliario']);
            $table->unsignedBigInteger('entidad_id');
            $table->decimal('cantidad', 10, 2);
            $table->enum('origen_tipo', ['orden_compra', 'proyecto', 'manual'])->default('manual');
            $table->unsignedBigInteger('origen_id')->nullable();
            $table->enum('estado', ['pendiente', 'en_proceso', 'completado', 'cancelado'])->default('pendiente');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_finalizacion_estimada')->nullable();
            $table->date('fecha_finalizacion_real')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['entidad_tipo', 'entidad_id']);
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes_proceso_externo');
    }
};
