<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuesto_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();
            $table->foreignId('mobiliario_id')->constrained('mobiliarios')->restrictOnDelete();
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('precio_unitario', 12, 2)->nullable();
            $table->string('descripcion_override')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('notas_manuales')->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuesto_items');
    }
};
