<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuesto_versiones', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();
            $table->unsignedSmallInteger('numero_version');
            $table->json('snapshot');
            $table->string('motivo_cambio')->nullable();
            $table->foreignId('creado_por')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['presupuesto_id', 'numero_version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuesto_versiones');
    }
};
