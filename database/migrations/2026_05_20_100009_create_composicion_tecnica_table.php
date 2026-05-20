<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * BOM (Bill of Materials) - Technical composition of furniture
     */
    public function up(): void
    {
        Schema::create('composicion_tecnica', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobiliario_id')->constrained('mobiliarios')->cascadeOnDelete();
            $table->foreignId('insumo_id')->constrained('insumos')->restrictOnDelete();
            $table->decimal('cantidad', 10, 4);
            $table->unsignedInteger('version')->default(1);
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['mobiliario_id', 'insumo_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composicion_tecnica');
    }
};
