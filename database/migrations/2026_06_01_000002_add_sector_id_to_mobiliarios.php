<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure sectores uses InnoDB (WAMP may default to MyISAM)
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE sectores ENGINE=InnoDB');

        Schema::table('mobiliarios', function (Blueprint $table) {
            if (! Schema::hasColumn('mobiliarios', 'sector_id')) {
                $table->foreignId('sector_id')
                    ->nullable()
                    ->after('marca_id')
                    ->constrained('sectores')
                    ->nullOnDelete();
            } else {
                $table->foreign('sector_id')
                    ->references('id')
                    ->on('sectores')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('mobiliarios', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Sector::class);
            $table->dropColumn('sector_id');
        });
    }
};
