<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Convertir valores string existentes a JSON array antes de cambiar el tipo
        DB::table('proyectos')
            ->whereNotNull('manual_pdf')
            ->where('manual_pdf', '!=', '')
            ->whereRaw("manual_pdf NOT LIKE '[%'")
            ->update(['manual_pdf' => DB::raw('JSON_ARRAY(manual_pdf)')]);

        Schema::table('proyectos', function (Blueprint $table) {
            $table->json('manual_pdf')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->string('manual_pdf')->nullable()->change();
        });
    }
};
