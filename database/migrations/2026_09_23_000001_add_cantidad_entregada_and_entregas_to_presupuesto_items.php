<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presupuesto_items', function (Blueprint $table) {
            $table->unsignedInteger('cantidad_entregada')->default(0)->after('cantidad');
        });

        Schema::create('presupuesto_item_entregas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('presupuesto_item_id')->constrained('presupuesto_items')->cascadeOnDelete();
            $table->unsignedInteger('cantidad');
            $table->timestamp('entregado_at');
            $table->foreignId('entregado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->text('observaciones')->nullable();
            $table->timestamp('anulado_at')->nullable();
            $table->foreignId('anulado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->text('motivo_anulacion')->nullable();
            $table->timestamps();

            $table->index(['presupuesto_item_id', 'anulado_at']);
        });

        $items = DB::table('presupuesto_items')
            ->whereNotNull('entregado_at')
            ->get(['id', 'cantidad', 'entregado_at', 'entregado_por', 'entrega_observaciones']);

        foreach ($items as $item) {
            $cantidad = max(0, (int) $item->cantidad);

            DB::table('presupuesto_items')
                ->where('id', $item->id)
                ->update(['cantidad_entregada' => $cantidad]);

            if ($cantidad <= 0) {
                continue;
            }

            DB::table('presupuesto_item_entregas')->insert([
                'presupuesto_item_id' => $item->id,
                'cantidad'            => $cantidad,
                'entregado_at'        => $item->entregado_at,
                'entregado_por'       => $item->entregado_por,
                'observaciones'       => $item->entrega_observaciones,
                'created_at'          => $item->entregado_at,
                'updated_at'          => $item->entregado_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuesto_item_entregas');

        Schema::table('presupuesto_items', function (Blueprint $table) {
            $table->dropColumn('cantidad_entregada');
        });
    }
};
