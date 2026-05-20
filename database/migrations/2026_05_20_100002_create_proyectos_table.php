<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_interno')->unique();
            $table->foreignId('agencia_id')->constrained('agencias')->restrictOnDelete();
            $table->string('estado')->default('presupuestar');
            // Estados: presupuestar, presupuestado, confirmado, en_produccion, entregado
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_entrega_estimada')->nullable();
            $table->date('fecha_entrega_real')->nullable();
            $table->text('observaciones')->nullable();
            $table->json('timeline')->nullable(); // Array of timeline events
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
