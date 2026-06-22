<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presupuesto_items', function (Blueprint $table) {
            $table->timestamp('finalizado_at')->nullable()->after('orden');
            $table->foreignId('finalizado_por')->nullable()->after('finalizado_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('presupuesto_items', function (Blueprint $table) {
            $table->dropForeign(['finalizado_por']);
            $table->dropColumn(['finalizado_at', 'finalizado_por']);
        });
    }
};
