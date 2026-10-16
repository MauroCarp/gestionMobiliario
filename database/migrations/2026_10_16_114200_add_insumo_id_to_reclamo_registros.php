<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reclamo_registros', function (Blueprint $table) {
            $table->dropForeign(['mobiliario_id']);
        });

        Schema::table('reclamo_registros', function (Blueprint $table) {
            $table->unsignedBigInteger('mobiliario_id')->nullable()->change();
            $table->unsignedBigInteger('insumo_id')->nullable()->after('mobiliario_id');

            $table->foreign('mobiliario_id')->references('id')->on('mobiliarios')->restrictOnDelete();
            $table->index('insumo_id');
        });
    }

    public function down(): void
    {
        Schema::table('reclamo_registros', function (Blueprint $table) {
            $table->dropForeign(['mobiliario_id']);
            $table->dropIndex(['insumo_id']);
            $table->dropColumn('insumo_id');
            $table->unsignedBigInteger('mobiliario_id')->nullable(false)->change();
            $table->foreign('mobiliario_id')->references('id')->on('mobiliarios')->restrictOnDelete();
        });
    }
};
