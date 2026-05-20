<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas_stock', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('presupuesto_id');
            $table->unsignedBigInteger('insumo_id');
            $table->decimal('cantidad_reservada', 10, 2);
            $table->enum('estado', ['activa', 'liberada', 'consumida'])->default('activa');
            $table->timestamps();

            $table->index(['presupuesto_id', 'estado']);
            $table->index(['insumo_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas_stock');
    }
};
