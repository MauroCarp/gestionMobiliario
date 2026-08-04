<?php

declare(strict_types=1);

use App\Models\Insumo;
use App\Models\Presupuesto;
use Illuminate\Contracts\Console\Kernel;

/**
 * Consulta de demanda de un insumo (solo lectura).
 *
 * No persiste ni modifica la base de datos: únicamente ejecuta consultas
 * de lectura (SELECT) sobre insumos, presupuestos e ítems relacionados.
 *
 * @return array{
 *     solo_lectura: true,
 *     codigo: string,
 *     nombre: string,
 *     insumo_id: int,
 *     cantidad_requerida: float,
 *     stock_actual: float,
 *     presupuestos: list<array{
 *         presupuesto_id: int,
 *         codigo: string,
 *         estado: string,
 *         estado_label: string,
 *         cantidad_requerida: float
 *     }>
 * }
 *
 * @throws InvalidArgumentException si no existe un insumo con ese código
 */
function demandaInsumoPorCodigo(string $codigoInsumo): array
{
    $codigoInsumo = trim($codigoInsumo);

    if ($codigoInsumo === '') {
        throw new InvalidArgumentException('El código de insumo no puede estar vacío.');
    }

    $insumo = Insumo::query()->where('codigo', $codigoInsumo)->first();

    if ($insumo === null) {
        throw new InvalidArgumentException("No se encontró un insumo con código «{$codigoInsumo}».");
    }

    $presupuestos = Presupuesto::query()
        ->with([
            'items' => fn ($q) => $q->whereNull('finalizado_at'),
            'items.mobiliario.composicionTecnica',
            'items.insumo',
        ])
        ->get();

    $requeridoTotal = 0.0;
    $porPresupuesto = [];

    foreach ($presupuestos as $presupuesto) {
        $requeridoEnPresupuesto = 0.0;

        foreach ($presupuesto->items as $item) {
            foreach ($item->demandaInsumos() as $insumoId => $cantidad) {
                if ((int) $insumoId === (int) $insumo->id) {
                    $requeridoEnPresupuesto += (float) $cantidad;
                }
            }
        }

        if ($requeridoEnPresupuesto <= 0) {
            continue;
        }

        $requeridoTotal += $requeridoEnPresupuesto;

        $porPresupuesto[] = [
            'presupuesto_id'       => (int) $presupuesto->id,
            'codigo'               => (string) $presupuesto->codigo,
            'estado'               => (string) $presupuesto->estado,
            'estado_label'         => Presupuesto::ESTADOS[$presupuesto->estado] ?? $presupuesto->estado,
            'cantidad_requerida'   => round($requeridoEnPresupuesto, 4),
        ];
    }

    usort(
        $porPresupuesto,
        fn (array $a, array $b): int => strcmp($a['codigo'], $b['codigo'])
    );

    return [
        'solo_lectura'         => true,
        'codigo'               => $insumo->codigo,
        'nombre'               => $insumo->nombre,
        'insumo_id'            => (int) $insumo->id,
        'cantidad_requerida'   => round($requeridoTotal, 4),
        'stock_actual'         => round((float) ($insumo->stock_actual ?? 0), 4),
        'presupuestos'         => $porPresupuesto,
    ];
}

/**
 * Arranca Laravel (idempotente si ya está bootstrapped).
 */
function demandaInsumoBootstrap(): void
{
    static $bootstrapped = false;

    if ($bootstrapped) {
        return;
    }

    $basePath = dirname(__DIR__);

    require $basePath . '/vendor/autoload.php';

    $app = require $basePath . '/bootstrap/app.php';
    $app->make(Kernel::class)->bootstrap();

    $bootstrapped = true;
}

if (PHP_SAPI === 'cli' && isset($argv[0]) && basename($argv[0]) === basename(__FILE__)) {
    demandaInsumoBootstrap();

    $codigo = $argv[1] ?? null;

    if ($codigo === null || trim((string) $codigo) === '') {
        fwrite(STDERR, "Uso: php scripts/consulta_demanda_insumo.php <codigo_insumo>\n");
        fwrite(STDERR, "Ejemplo: php scripts/consulta_demanda_insumo.php INS-0001\n");
        fwrite(STDERR, "Nota: consulta de solo lectura; no modifica la base de datos.\n");
        exit(1);
    }

    try {
        $resultado = demandaInsumoPorCodigo((string) $codigo);
        echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
        exit(0);
    } catch (InvalidArgumentException $e) {
        fwrite(STDERR, $e->getMessage() . PHP_EOL);
        exit(1);
    }
}
