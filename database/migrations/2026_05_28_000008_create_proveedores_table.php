<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('razon_social');
            $table->string('nombre_comercial')->nullable();
            $table->string('cuit')->nullable();
            $table->string('condicion_iva')->nullable();
            $table->string('direccion')->nullable();
            $table->unsignedBigInteger('provincia_id')->nullable();
            $table->unsignedBigInteger('ciudad_id')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('persona_contacto')->nullable();
            $table->boolean('activo')->default(true);
            $table->text('observaciones')->nullable();
            $table->string('rubro')->nullable();
            $table->string('condicion_pago')->nullable();
            $table->string('lista_precio')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
