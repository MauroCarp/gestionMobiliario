<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_compra_item_recepciones', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('orden_compra_item_id');
            $table->decimal('cantidad', 10, 2);
            $table->date('fecha_recepcion');
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index('orden_compra_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_compra_item_recepciones');
    }
};
