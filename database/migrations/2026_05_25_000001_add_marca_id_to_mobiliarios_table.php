<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mobiliarios', function (Blueprint $table) {
            if (! Schema::hasColumn('mobiliarios', 'marca_id')) {
                $table->foreignId('marca_id')
                    ->nullable()
                    ->after('categoria_id')
                    ->constrained('marcas')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('mobiliarios', function (Blueprint $table) {
            $table->dropForeign(['marca_id']);
            $table->dropColumn('marca_id');
        });
    }
};
