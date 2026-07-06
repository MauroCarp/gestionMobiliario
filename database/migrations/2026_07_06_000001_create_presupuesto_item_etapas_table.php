<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuesto_item_etapas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_item_id')->constrained('presupuesto_items')->cascadeOnDelete();
            $table->string('nombre');
            $table->unsignedTinyInteger('orden');
            $table->enum('estado', ['pendiente', 'en_proceso', 'completado'])->default('pendiente');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->foreignId('iniciado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('completado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->unique(['presupuesto_item_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuesto_item_etapas');
    }
};
