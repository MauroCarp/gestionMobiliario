<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordenes_produccion', function (Blueprint $table) {
            $table->dropIndex(['fecha_compromiso']);
            $table->dropColumn(['prioridad', 'fecha_compromiso']);
        });

        Schema::table('orden_produccion_items', function (Blueprint $table) {
            $table->json('insumos_seleccionados')->nullable()->after('observaciones');
        });
    }

    public function down(): void
    {
        Schema::table('orden_produccion_items', function (Blueprint $table) {
            $table->dropColumn('insumos_seleccionados');
        });

        Schema::table('ordenes_produccion', function (Blueprint $table) {
            $table->string('prioridad')->default('media')->after('estado');
            $table->date('fecha_compromiso')->nullable()->after('fecha_inicio');
            $table->index('fecha_compromiso');
        });
    }
};
