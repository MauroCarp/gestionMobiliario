<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            // Agencia is now chosen per-presupuesto, filtered by the project's brand
            if (! Schema::hasColumn('presupuestos', 'agencia_id')) {
                $after = Schema::hasColumn('presupuestos', 'proyecto_id') ? 'proyecto_id' : 'codigo';
                $table->unsignedBigInteger('agencia_id')->nullable()->after($after);
            }
        });
    }

    public function down(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropColumn('agencia_id');
        });
    }
};
