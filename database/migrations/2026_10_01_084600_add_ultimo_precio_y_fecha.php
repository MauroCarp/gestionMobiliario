<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->date('fecha_ultimo_precio')->nullable()->after('precio_costo');
        });

        Schema::table('orden_compra_item_recepciones', function (Blueprint $table) {
            $table->decimal('costo_unitario', 10, 2)->nullable()->after('cantidad');
        });

        Schema::table('plantilla_etapas', function (Blueprint $table) {
            $table->decimal('ultimo_precio', 10, 2)->nullable()->after('dias_estimados');
            $table->date('fecha_ultimo_precio')->nullable()->after('ultimo_precio');
        });
    }

    public function down(): void
    {
        Schema::table('plantilla_etapas', function (Blueprint $table) {
            $table->dropColumn(['ultimo_precio', 'fecha_ultimo_precio']);
        });

        Schema::table('orden_compra_item_recepciones', function (Blueprint $table) {
            $table->dropColumn('costo_unitario');
        });

        Schema::table('insumos', function (Blueprint $table) {
            $table->dropColumn('fecha_ultimo_precio');
        });
    }
};
