<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mobiliarios', function (Blueprint $table) {
            $table->decimal('precio', 12, 2)->nullable()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('mobiliarios', function (Blueprint $table) {
            $table->dropColumn('precio');
        });
    }
};
