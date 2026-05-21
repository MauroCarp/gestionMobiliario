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
            $table->unsignedBigInteger('agencia_id')->nullable()->after('proyecto_id');
        });
    }

    public function down(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropColumn('agencia_id');
        });
    }
};
