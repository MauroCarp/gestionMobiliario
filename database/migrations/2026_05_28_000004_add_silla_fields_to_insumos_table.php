<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->foreignId('proveedor_id')
                ->nullable()
                ->after('categoria_insumo_id')
                ->constrained('terceros')
                ->nullOnDelete();

            $table->foreignId('tipo_silla_id')
                ->nullable()
                ->after('proveedor_id')
                ->constrained('tipos_silla')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->dropForeign(['proveedor_id']);
            $table->dropForeign(['tipo_silla_id']);
            $table->dropColumn(['proveedor_id', 'tipo_silla_id']);
        });
    }
};
