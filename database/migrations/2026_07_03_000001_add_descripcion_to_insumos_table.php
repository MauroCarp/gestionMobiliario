<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            if (! Schema::hasColumn('insumos', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('tipo_silla_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            if (Schema::hasColumn('insumos', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
        });
    }
};
