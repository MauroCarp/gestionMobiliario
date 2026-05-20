<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->decimal('stock_actual', 10, 2)->default(0)->after('stock_minimo');
            $table->decimal('precio_costo', 10, 2)->nullable()->after('stock_actual');
        });
    }

    public function down(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->dropColumn(['stock_actual', 'precio_costo']);
        });
    }
};
