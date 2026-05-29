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
        Schema::create('proveedor_rubro', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('rubro_id');
            $table->timestamps();

            $table->unique(['proveedor_id', 'rubro_id']);
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('cascade');
            $table->foreign('rubro_id')->references('id')->on('rubros')->onDelete('cascade');
        });

        // Migrar datos existentes de rubro_id al pivote
        if (Schema::hasColumn('proveedores', 'rubro_id')) {
            DB::table('proveedores')
                ->whereNotNull('rubro_id')
                ->select('id', 'rubro_id')
                ->orderBy('id')
                ->each(function ($row) {
                    DB::table('proveedor_rubro')->insert([
                        'proveedor_id' => $row->id,
                        'rubro_id'     => $row->rubro_id,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                });

            // Eliminar columna rubro_id
            Schema::table('proveedores', function (Blueprint $table) {
                $table->dropColumn('rubro_id');
            });
        }
    }

    public function down(): void
    {
        // Restaurar columna rubro_id (toma el primer rubro si hay varios)
        Schema::table('proveedores', function (Blueprint $table) {
            $table->unsignedBigInteger('rubro_id')->nullable()->after('observaciones');
        });

        DB::table('proveedor_rubro')
            ->select('proveedor_id', DB::raw('MIN(rubro_id) as rubro_id'))
            ->groupBy('proveedor_id')
            ->orderBy('proveedor_id')
            ->each(function ($row) {
                DB::table('proveedores')
                    ->where('id', $row->proveedor_id)
                    ->update(['rubro_id' => $row->rubro_id]);
            });

        Schema::dropIfExists('proveedor_rubro');
    }
};
