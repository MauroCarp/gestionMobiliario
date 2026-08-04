<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->boolean('logistica_instalacion_propia')->default(true)->after('dias_entrega');
            $table->text('logistica_leyenda')->nullable()->after('logistica_instalacion_propia');
            $table->decimal('logistica_costo', 12, 2)->default(0)->after('logistica_leyenda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropColumn([
                'logistica_instalacion_propia',
                'logistica_leyenda',
                'logistica_costo',
            ]);
        });
    }
};
