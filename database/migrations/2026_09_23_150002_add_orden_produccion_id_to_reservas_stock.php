<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas_stock', function (Blueprint $table) {
            $table->unsignedBigInteger('presupuesto_id')->nullable()->change();
            $table->unsignedBigInteger('orden_produccion_id')->nullable()->after('presupuesto_id');
            $table->index(['orden_produccion_id', 'estado'], 'reservas_stock_op_estado_index');
        });
    }

    public function down(): void
    {
        Schema::table('reservas_stock', function (Blueprint $table) {
            $table->dropIndex('reservas_stock_op_estado_index');
            $table->dropColumn('orden_produccion_id');
            $table->unsignedBigInteger('presupuesto_id')->nullable(false)->change();
        });
    }
};
