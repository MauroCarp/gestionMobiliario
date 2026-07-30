<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plantillas_flujo_externo', function (Blueprint $table) {
            $table->unsignedInteger('stock_casco')->default(0)->after('activo');
        });
    }

    public function down(): void
    {
        Schema::table('plantillas_flujo_externo', function (Blueprint $table) {
            $table->dropColumn('stock_casco');
        });
    }
};
