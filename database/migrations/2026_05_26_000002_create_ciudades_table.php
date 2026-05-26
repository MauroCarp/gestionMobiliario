<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('provincia_id')->constrained('provincias')->cascadeOnDelete();
            $table->string('codigo_postal')->nullable();
            $table->unique(['nombre', 'provincia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ciudades');
    }
};
