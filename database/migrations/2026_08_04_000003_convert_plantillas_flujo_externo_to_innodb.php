<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE plantillas_flujo_externo ENGINE = InnoDB');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE plantillas_flujo_externo ENGINE = MyISAM');
    }
};
