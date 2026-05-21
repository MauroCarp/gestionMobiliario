<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            // Direct brand reference on the project
            $table->unsignedBigInteger('marca_id')->nullable()->after('codigo_interno');
            // Make agencia_id optional — agencia is now chosen at presupuesto level
            $table->unsignedBigInteger('agencia_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn('marca_id');
            $table->unsignedBigInteger('agencia_id')->nullable(false)->change();
        });
    }
};
