<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo_interno')->unique();
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->nullOnDelete();
            $table->string('direccion')->nullable();
            $table->string('responsable')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->unsignedTinyInteger('prioridad')->default(3); // 1=Alta, 2=Media, 3=Baja
            $table->json('etiquetas')->nullable(); // Tags array
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agencias');
    }
};
