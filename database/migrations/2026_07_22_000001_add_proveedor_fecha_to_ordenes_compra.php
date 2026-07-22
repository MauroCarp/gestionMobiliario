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
            $table->unsignedBigInteger('proveedor_id')->nullable()->after('presupuesto_id');
            $table->date('fecha_pactada_entrega')->nullable()->after('proveedor_id');

            $table->index('proveedor_id');
        });

        DB::statement("ALTER TABLE ordenes_compra MODIFY COLUMN estado ENUM('sugerida', 'pendiente', 'aprobada', 'recibida_parcial', 'recibida', 'cancelada') NOT NULL DEFAULT 'sugerida'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ordenes_compra MODIFY COLUMN estado ENUM('sugerida', 'pendiente', 'aprobada', 'recibida', 'cancelada') NOT NULL DEFAULT 'sugerida'");

        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->dropIndex(['proveedor_id']);
            $table->dropColumn(['proveedor_id', 'fecha_pactada_entrega']);
        });
    }
};
