<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Quitar campos de silla de insumos (MyISAM - no tiene FK reales; columnas pueden no existir)
        Schema::table('insumos', function (Blueprint $table) {
            if (Schema::hasColumn('insumos', 'proveedor_id')) {
                $table->dropColumn('proveedor_id');
            }
            if (Schema::hasColumn('insumos', 'tipo_silla_id')) {
                $table->dropColumn('tipo_silla_id');
            }
        });

        Schema::dropIfExists('insumo_marcas_silla');

        // Agregar campos de silla a mobiliarios (sin FK hacia tablas MyISAM)
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

    public function down(): void
    {
        Schema::dropIfExists('mobiliario_marcas_silla');

        Schema::table('mobiliarios', function (Blueprint $table) {
            $table->dropColumn(['proveedor_id', 'tipo_silla_id']);
        });

        Schema::create('insumo_marcas_silla', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insumo_id')->nullable();
            $table->unsignedBigInteger('marca_id')->nullable();
            $table->string('nombre_fantasia')->nullable();
            $table->timestamps();
        });

        Schema::table('insumos', function (Blueprint $table) {
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->unsignedBigInteger('tipo_silla_id')->nullable();
        });
    }
};
