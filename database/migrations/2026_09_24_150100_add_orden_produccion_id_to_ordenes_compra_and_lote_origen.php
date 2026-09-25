<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->unsignedBigInteger('orden_produccion_id')->nullable()->after('presupuesto_id');
            $table->index('orden_produccion_id');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE lotes_proceso_externo MODIFY origen_tipo ENUM('orden_compra', 'proyecto', 'manual', 'orden_produccion') DEFAULT 'manual'");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE lotes_proceso_externo MODIFY origen_tipo ENUM('orden_compra', 'proyecto', 'manual') DEFAULT 'manual'");
        }

        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->dropIndex(['orden_produccion_id']);
            $table->dropColumn('orden_produccion_id');
        });
    }
};
