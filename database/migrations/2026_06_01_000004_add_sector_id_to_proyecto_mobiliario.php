<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyecto_mobiliario', function (Blueprint $table) {
            $table->foreignId('sector_id')
                ->nullable()
                ->after('mobiliario_id')
                ->constrained('sectores')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('proyecto_mobiliario', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn('sector_id');
        });
    }
};
