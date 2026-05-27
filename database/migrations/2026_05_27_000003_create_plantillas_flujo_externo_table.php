<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantillas_flujo_externo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('entidad_tipo', ['insumo', 'mobiliario']);
            $table->unsignedBigInteger('entidad_id');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['entidad_tipo', 'entidad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantillas_flujo_externo');
    }
};
