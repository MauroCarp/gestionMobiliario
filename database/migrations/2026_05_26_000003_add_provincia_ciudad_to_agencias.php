<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencias', function (Blueprint $table) {
            $table->foreignId('provincia_id')->nullable()->after('direccion')->constrained('provincias')->nullOnDelete();
            $table->foreignId('ciudad_id')->nullable()->after('provincia_id')->constrained('ciudades')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('agencias', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['provincia_id']);
            $table->dropForeignKeyIfExists(['ciudad_id']);
            $table->dropColumn(['provincia_id', 'ciudad_id']);
        });
    }
};
