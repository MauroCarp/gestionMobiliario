<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Restructura las relaciones principales:
 *
 *  - Proyecto  → tiene muchas Agencias (1:N), una Marca, muchos Mobiliarios
 *  - Agencia   → pertenece a un Proyecto, tiene muchos Presupuestos
 *  - Presupuesto → pertenece a una Agencia (el Proyecto se obtiene a través de ella)
 *
 * Campos eliminados para evitar duplicación de datos:
 *  - agencias.marca_id      (la marca viene del proyecto al que pertenece la agencia)
 *  - proyectos.agencia_id   (un proyecto tiene MUCHAS agencias, no al revés)
 *  - presupuestos.proyecto_id (el proyecto se obtiene vía agencia → proyecto)
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. presupuestos: quitar proyecto_id (redundante vía agencia)
        Schema::table('presupuestos', function (Blueprint $table) {
            $fks = collect(\DB::select("
                SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'presupuestos'
                  AND COLUMN_NAME = 'proyecto_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            "))->pluck('CONSTRAINT_NAME');

            foreach ($fks as $fk) {
                $table->dropForeign($fk);
            }

            if (Schema::hasColumn('presupuestos', 'proyecto_id')) {
                $table->dropColumn('proyecto_id');
            }
        });

        // 2. proyectos: quitar agencia_id (el proyecto ya no pertenece a una agencia)
        Schema::table('proyectos', function (Blueprint $table) {
            $fks = collect(\DB::select("
                SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'proyectos'
                  AND COLUMN_NAME = 'agencia_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            "))->pluck('CONSTRAINT_NAME');

            foreach ($fks as $fk) {
                $table->dropForeign($fk);
            }

            if (Schema::hasColumn('proyectos', 'agencia_id')) {
                $table->dropColumn('agencia_id');
            }
        });

        // 3. agencias: quitar marca_id (redundante) y agregar proyecto_id
        Schema::table('agencias', function (Blueprint $table) {
            $fks = collect(\DB::select("
                SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'agencias'
                  AND COLUMN_NAME = 'marca_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            "))->pluck('CONSTRAINT_NAME');

            foreach ($fks as $fk) {
                $table->dropForeign($fk);
            }

            if (Schema::hasColumn('agencias', 'marca_id')) {
                $table->dropColumn('marca_id');
            }

            if (! Schema::hasColumn('agencias', 'proyecto_id')) {
                $table->foreignId('proyecto_id')
                    ->nullable()
                    ->after('codigo_interno')
                    ->constrained('proyectos')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        // Invertir en orden contrario

        // 3. agencias: restaurar marca_id, quitar proyecto_id
        Schema::table('agencias', function (Blueprint $table) {
            $table->dropForeign(['proyecto_id']);
            $table->dropColumn('proyecto_id');

            $table->foreignId('marca_id')
                ->nullable()
                ->after('codigo_interno')
                ->constrained('marcas')
                ->nullOnDelete();
        });

        // 2. proyectos: restaurar agencia_id (nullable)
        Schema::table('proyectos', function (Blueprint $table) {
            $table->foreignId('agencia_id')
                ->nullable()
                ->after('codigo_interno')
                ->constrained('agencias')
                ->nullOnDelete();
        });

        // 1. presupuestos: restaurar proyecto_id
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->foreignId('proyecto_id')
                ->nullable()
                ->after('codigo')
                ->constrained('proyectos')
                ->nullOnDelete();
        });
    }
};
