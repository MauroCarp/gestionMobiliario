<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot table linking projects to furniture items
     */
    public function up(): void
    {
        Schema::create('proyecto_mobiliario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->foreignId('mobiliario_id')->constrained('mobiliarios')->restrictOnDelete();
            $table->unsignedInteger('cantidad')->default(1);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyecto_mobiliario');
    }
};
