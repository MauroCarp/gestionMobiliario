<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobiliarios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_interno')->unique();
            $table->string('nombre');
            $table->foreignId('categoria_id')->constrained('categorias_mobiliario')->restrictOnDelete();
            $table->string('imagen')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('estado')->default('activo'); // activo, inactivo, discontinuado
            $table->unsignedInteger('version_actual')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobiliarios');
    }
};
