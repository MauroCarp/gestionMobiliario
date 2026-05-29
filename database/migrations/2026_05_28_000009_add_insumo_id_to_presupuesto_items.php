<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presupuesto_items', function (Blueprint $table) {
            // Hacer mobiliario_id nullable para permitir ítems de tipo insumo (sillas)
            $table->dropForeign(['mobiliario_id']);
            $table->unsignedBigInteger('mobiliario_id')->nullable()->change();
            $table->foreign('mobiliario_id')->references('id')->on('mobiliarios')->restrictOnDelete();

            // Columna para insumos de categoría Sillas
            $table->unsignedBigInteger('insumo_id')->nullable()->after('mobiliario_id');
        });
    }

    public function down(): void
    {
        Schema::table('presupuesto_items', function (Blueprint $table) {
            $table->dropColumn('insumo_id');

            $table->dropForeign(['mobiliario_id']);
            $table->unsignedBigInteger('mobiliario_id')->nullable(false)->change();
            $table->foreign('mobiliario_id')->references('id')->on('mobiliarios')->restrictOnDelete();
        });
    }
};
