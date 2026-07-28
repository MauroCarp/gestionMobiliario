<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mobiliarios', function (Blueprint $table) {
            $table->unsignedInteger('stock_actual')->default(0)->after('precio');
        });

        Schema::table('presupuesto_items', function (Blueprint $table) {
            $table->unsignedInteger('cantidad_desde_stock')->default(0)->after('cantidad');
            $table->unsignedInteger('cantidad_a_fabricar')->default(0)->after('cantidad_desde_stock');
            $table->timestamp('stock_descontado_at')->nullable()->after('cantidad_a_fabricar');
            $table->timestamp('stock_reintegrado_at')->nullable()->after('stock_descontado_at');
        });

        DB::table('presupuesto_items')
            ->whereNotNull('mobiliario_id')
            ->update([
                'cantidad_desde_stock' => 0,
                'cantidad_a_fabricar'  => DB::raw('cantidad'),
            ]);
    }

    public function down(): void
    {
        Schema::table('presupuesto_items', function (Blueprint $table) {
            $table->dropColumn([
                'cantidad_desde_stock',
                'cantidad_a_fabricar',
                'stock_descontado_at',
                'stock_reintegrado_at',
            ]);
        });

        Schema::table('mobiliarios', function (Blueprint $table) {
            $table->dropColumn('stock_actual');
        });
    }
};
