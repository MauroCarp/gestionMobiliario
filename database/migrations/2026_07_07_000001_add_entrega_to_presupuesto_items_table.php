<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presupuesto_items', function (Blueprint $table) {
            $table->timestamp('entregado_at')->nullable()->after('finalizado_por');
            $table->foreignId('entregado_por')->nullable()->after('entregado_at')->constrained('users')->nullOnDelete();
            $table->text('entrega_observaciones')->nullable()->after('entregado_por');
        });
    }

    public function down(): void
    {
        Schema::table('presupuesto_items', function (Blueprint $table) {
            $table->dropForeign(['entregado_por']);
            $table->dropColumn(['entregado_at', 'entregado_por', 'entrega_observaciones']);
        });
    }
};
