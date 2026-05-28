<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Quitar campos de silla de mobiliarios
        Schema::table('mobiliarios', function (Blueprint $table) {
            if (Schema::hasColumn('mobiliarios', 'tipo_silla_id')) {
                $table->dropColumn('tipo_silla_id');
            }
            if (Schema::hasColumn('mobiliarios', 'proveedor_id')) {
                $table->dropColumn('proveedor_id');
            }
        });

        Schema::dropIfExists('mobiliario_marcas_silla');

        // Re-agregar campos de silla a insumos
        Schema::table('insumos', function (Blueprint $table) {
            if (!Schema::hasColumn('insumos', 'proveedor_id')) {
                $table->unsignedBigInteger('proveedor_id')->nullable()->after('activo');
            }
            if (!Schema::hasColumn('insumos', 'tipo_silla_id')) {
                $table->unsignedBigInteger('tipo_silla_id')->nullable()->after('proveedor_id');
            }
        });

        Schema::create('insumo_marcas_silla', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insumo_id')->constrained('insumos')->cascadeOnDelete();
            $table->unsignedBigInteger('marca_id')->nullable();
            $table->string('nombre_fantasia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insumo_marcas_silla');

        Schema::table('insumos', function (Blueprint $table) {
            if (Schema::hasColumn('insumos', 'tipo_silla_id')) {
                $table->dropColumn('tipo_silla_id');
            }
            if (Schema::hasColumn('insumos', 'proveedor_id')) {
                $table->dropColumn('proveedor_id');
            }
        });

        Schema::table('mobiliarios', function (Blueprint $table) {
            if (!Schema::hasColumn('mobiliarios', 'proveedor_id')) {
                $table->unsignedBigInteger('proveedor_id')->nullable()->after('precio');
            }
            if (!Schema::hasColumn('mobiliarios', 'tipo_silla_id')) {
                $table->unsignedBigInteger('tipo_silla_id')->nullable()->after('proveedor_id');
            }
        });

        Schema::create('mobiliario_marcas_silla', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobiliario_id')->constrained('mobiliarios')->cascadeOnDelete();
            $table->unsignedBigInteger('marca_id')->nullable();
            $table->string('nombre_fantasia')->nullable();
            $table->timestamps();
        });
    }
};
