<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('agencia_id');
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->restrictOnDelete();
            $table->timestamps();

            $table->index('agencia_id');
            $table->index(['agencia_id', 'presupuesto_id']);
        });

        Schema::create('reclamo_registros', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('reclamo_id')->constrained('reclamos')->cascadeOnDelete();
            $table->foreignId('mobiliario_id')->constrained('mobiliarios')->restrictOnDelete();
            $table->text('observacion')->nullable();
            $table->date('fecha');
            $table->string('estado', 20)->default('pendiente');
            $table->decimal('costo', 12, 2)->nullable();
            $table->decimal('costo_logistica', 12, 2)->nullable();
            $table->date('fecha_resuelto')->nullable();
            $table->timestamps();

            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamo_registros');
        Schema::dropIfExists('reclamos');
    }
};
