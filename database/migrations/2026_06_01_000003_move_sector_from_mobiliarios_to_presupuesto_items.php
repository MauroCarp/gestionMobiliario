<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove sector_id from mobiliarios
        Schema::table('mobiliarios', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn('sector_id');
        });

        // Add sector_id to presupuesto_items
        Schema::table('presupuesto_items', function (Blueprint $table) {
            $table->foreignId('sector_id')
                ->nullable()
                ->after('insumo_id')
                ->constrained('sectores')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('presupuesto_items', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn('sector_id');
        });

        Schema::table('mobiliarios', function (Blueprint $table) {
            $table->foreignId('sector_id')
                ->nullable()
                ->after('marca_id')
                ->constrained('sectores')
                ->nullOnDelete();
        });
    }
};
