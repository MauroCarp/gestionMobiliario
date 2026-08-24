<?php

use App\Models\Presupuesto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->text('metodo_pago')->nullable(false)->change();
        });

        DB::table('presupuestos')
            ->select('id', 'metodo_pago', 'dias_entrega')
            ->orderBy('id')
            ->each(function (object $row): void {
                $clave = (string) $row->metodo_pago;

                if (! in_array($clave, ['defecto', 'transferencia', ''], true)) {
                    return;
                }

                $formaPago = $clave === 'transferencia'
                    ? 'TRANSFERENCIA.'
                    : Presupuesto::TEXTO_PAGO_DEFECTO;

                DB::table('presupuestos')->where('id', $row->id)->update([
                    'metodo_pago' => Presupuesto::textoBasesCondicionesDefault(
                        $formaPago,
                        (int) ($row->dias_entrega ?: 50)
                    ),
                ]);
            });

        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropColumn('dias_entrega');
        });
    }

    public function down(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->unsignedSmallInteger('dias_entrega')->default(50)->after('metodo_pago');
        });

        DB::table('presupuestos')
            ->select('id', 'metodo_pago')
            ->orderBy('id')
            ->each(function (object $row): void {
                $texto = (string) $row->metodo_pago;
                $clave = str_contains($texto, 'TRANSFERENCIA.') && ! str_contains($texto, Presupuesto::TEXTO_PAGO_DEFECTO)
                    ? 'transferencia'
                    : 'defecto';

                $dias = 50;
                if (preg_match('/PLAZO DE ENTREGA:\s*(\d+)\s*días/u', $texto, $matches)) {
                    $dias = (int) $matches[1];
                }

                DB::table('presupuestos')->where('id', $row->id)->update([
                    'metodo_pago' => $clave,
                    'dias_entrega' => $dias,
                ]);
            });

        Schema::table('presupuestos', function (Blueprint $table) {
            $table->string('metodo_pago')->default('defecto')->change();
        });
    }
};
