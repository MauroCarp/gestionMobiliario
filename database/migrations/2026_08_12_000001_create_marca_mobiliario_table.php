<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('marca_mobiliario')) {
            Schema::create('marca_mobiliario', function (Blueprint $table) {
                $table->foreignId('mobiliario_id')->constrained('mobiliarios')->cascadeOnDelete();
                $table->foreignId('marca_id')->constrained('marcas')->cascadeOnDelete();
                $table->unique(['mobiliario_id', 'marca_id']);
            });
        }

        if (Schema::hasColumn('mobiliarios', 'marca_id')) {
            DB::table('mobiliarios')
                ->whereNotNull('marca_id')
                ->select('id', 'marca_id')
                ->orderBy('id')
                ->chunk(500, function ($rows): void {
                    foreach ($rows as $row) {
                        DB::table('marca_mobiliario')->insertOrIgnore([
                            'mobiliario_id' => $row->id,
                            'marca_id' => $row->marca_id,
                        ]);
                    }
                });

            Schema::table('mobiliarios', function (Blueprint $table) {
                $foreignKeys = collect(DB::select('SHOW CREATE TABLE mobiliarios'))
                    ->first();

                $createSql = $foreignKeys->{'Create Table'} ?? '';

                if (str_contains($createSql, 'mobiliarios_marca_id_foreign')) {
                    $table->dropForeign(['marca_id']);
                }

                if (Schema::hasColumn('mobiliarios', 'marca_id')) {
                    $table->dropColumn('marca_id');
                }
            });
        }
    }

    public function down(): void
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

        if (Schema::hasTable('marca_mobiliario')) {
            DB::table('marca_mobiliario')
                ->select('mobiliario_id', DB::raw('MIN(marca_id) as marca_id'))
                ->groupBy('mobiliario_id')
                ->orderBy('mobiliario_id')
                ->chunk(500, function ($rows): void {
                    foreach ($rows as $row) {
                        DB::table('mobiliarios')
                            ->where('id', $row->mobiliario_id)
                            ->update(['marca_id' => $row->marca_id]);
                    }
                });

            Schema::dropIfExists('marca_mobiliario');
        }
    }
};
