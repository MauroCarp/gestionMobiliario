<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Quitar codigo_interno de agencias (ya no es necesario, el nombre es suficiente)
        Schema::table('agencias', function (Blueprint $table) {
            if (Schema::hasColumn('agencias', 'codigo_interno')) {
                $table->dropUnique(['codigo_interno']);
                $table->dropColumn('codigo_interno');
            }
        });

        // Quitar colores de marcas (no se utiliza)
        Schema::table('marcas', function (Blueprint $table) {
            if (Schema::hasColumn('marcas', 'colores')) {
                $table->dropColumn('colores');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agencias', function (Blueprint $table) {
            $table->string('codigo_interno')->nullable()->unique()->after('nombre');
        });

        Schema::table('marcas', function (Blueprint $table) {
            $table->json('colores')->nullable()->after('logo');
        });
    }
};
