<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_versiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobiliario_id')->constrained('mobiliarios')->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->string('descripcion_cambio');
            $table->json('snapshot')->nullable(); // Snapshot of BOM at that version
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['mobiliario_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_versiones');
    }
};
