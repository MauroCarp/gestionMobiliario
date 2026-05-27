<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lote_etapas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_id')->constrained('lotes_proceso_externo')->cascadeOnDelete();
            $table->unsignedTinyInteger('orden')->default(1);
            $table->foreignId('tipo_proceso_id')->constrained('tipos_proceso_externo');
            $table->foreignId('tercero_id')->nullable()->constrained('terceros')->nullOnDelete();
            $table->enum('estado', ['pendiente', 'en_transito', 'en_proceso', 'completado'])->default('pendiente');
            $table->date('fecha_envio')->nullable();
            $table->date('fecha_recepcion_estimada')->nullable();
            $table->date('fecha_recepcion_real')->nullable();
            $table->decimal('costo', 10, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lote_etapas');
    }
};
