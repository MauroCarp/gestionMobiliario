<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Crear tabla pivote
        Schema::create('categoria_insumo_insumo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insumo_id');
            $table->unsignedBigInteger('categoria_insumo_id');
            $table->timestamps();

            $table->unique(['insumo_id', 'categoria_insumo_id']);
            $table->foreign('insumo_id')->references('id')->on('insumos')->onDelete('cascade');
            $table->foreign('categoria_insumo_id')->references('id')->on('categorias_insumo')->onDelete('cascade');
        });

        // Migrar datos existentes
        if (Schema::hasColumn('insumos', 'categoria_insumo_id')) {
            DB::table('insumos')
                ->whereNotNull('categoria_insumo_id')
                ->select('id', 'categoria_insumo_id')
                ->orderBy('id')
                ->each(function ($row) {
                    DB::table('categoria_insumo_insumo')->insert([
                        'insumo_id'          => $row->id,
                        'categoria_insumo_id' => $row->categoria_insumo_id,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);
                });

            // Eliminar columna
            Schema::table('insumos', function (Blueprint $table) {
                $table->dropColumn('categoria_insumo_id');
            });
        }
    }

    public function down(): void
    {
        // Restaurar columna (toma la primera categoría si hay varias)
        Schema::table('insumos', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_insumo_id')->nullable()->after('unidad_medida_id');
        });

        DB::table('categoria_insumo_insumo')
            ->select('insumo_id', DB::raw('MIN(categoria_insumo_id) as categoria_insumo_id'))
            ->groupBy('insumo_id')
            ->orderBy('insumo_id')
            ->each(function ($row) {
                DB::table('insumos')
                    ->where('id', $row->insumo_id)
                    ->update(['categoria_insumo_id' => $row->categoria_insumo_id]);
            });

        Schema::dropIfExists('categoria_insumo_insumo');
    }
};
