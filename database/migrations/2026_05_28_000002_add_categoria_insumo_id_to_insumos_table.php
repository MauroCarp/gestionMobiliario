<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->foreignId('categoria_insumo_id')
                ->nullable()
                ->after('unidad_medida_id')
                ->constrained('categorias_insumo')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->dropForeign(['categoria_insumo_id']);
            $table->dropColumn('categoria_insumo_id');
        });
    }
};
