<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insumo_marcas_silla', function (Blueprint $table) {
            $table->decimal('precio', 12, 2)->nullable()->after('nombre_fantasia');
        });
    }

    public function down(): void
    {
        Schema::table('insumo_marcas_silla', function (Blueprint $table) {
            $table->dropColumn('precio');
        });
    }
};
