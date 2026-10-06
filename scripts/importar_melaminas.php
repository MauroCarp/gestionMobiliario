<?php

declare(strict_types=1);

use App\Models\CategoriaInsumo;
use App\Models\Insumo;
use App\Models\UnidadMedida;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

const UNIDAD_MEDIDA_ID = 4;
const CATEGORIA_INSUMO_ID = 28;

/**
 * Arranca Laravel (idempotente si ya está bootstrapped).
 */
function importarMelaminasBootstrap(): void
{
    static $bootstrapped = false;

    if ($bootstrapped) {
        return;
    }

    $basePath = dirname(__DIR__);

    require $basePath.'/vendor/autoload.php';

    $app = require $basePath.'/bootstrap/app.php';
    $app->make(Kernel::class)->bootstrap();

    $bootstrapped = true;
}

function parsePrecioArgentino(string $raw): ?float
{
    $valor = trim(str_replace(['$', ' '], '', $raw));

    if ($valor === '' || $valor === '-') {
        return null;
    }

    $valor = str_replace('.', '', $valor);
    $valor = str_replace(',', '.', $valor);

    return is_numeric($valor) ? (float) $valor : null;
}

function parseFechaCsv(string $raw): ?string
{
    $raw = trim($raw);

    if ($raw === '') {
        return null;
    }

    $fecha = DateTime::createFromFormat('!j/n/Y', $raw)
        ?: DateTime::createFromFormat('!d/m/Y', $raw);

    return $fecha instanceof DateTime ? $fecha->format('Y-m-d') : null;
}

function siguienteNumeroCodigo(): int
{
    $codigos = Insumo::query()
        ->where('codigo', 'like', 'INS-%')
        ->pluck('codigo');

    $ultimo = 0;

    foreach ($codigos as $codigo) {
        if (preg_match('/^INS-(\d+)$/', (string) $codigo, $matches) !== 1) {
            continue;
        }

        $ultimo = max($ultimo, (int) $matches[1]);
    }

    return $ultimo + 1;
}

function codigoInsumo(int $numero): string
{
    return 'INS-'.str_pad((string) $numero, 4, '0', STR_PAD_LEFT);
}

function importarMelaminas(string $csvPath): array
{
    if (! file_exists($csvPath)) {
        throw new RuntimeException("Archivo no encontrado: {$csvPath}");
    }

    if (! UnidadMedida::query()->whereKey(UNIDAD_MEDIDA_ID)->exists()) {
        throw new RuntimeException('No existe la unidad de medida id '.UNIDAD_MEDIDA_ID.'.');
    }

    if (! CategoriaInsumo::query()->whereKey(CATEGORIA_INSUMO_ID)->exists()) {
        throw new RuntimeException('No existe la categoría de insumo id '.CATEGORIA_INSUMO_ID.'.');
    }

    $handle = fopen($csvPath, 'r');

    if ($handle === false) {
        throw new RuntimeException("No se pudo abrir: {$csvPath}");
    }

    fgetcsv($handle, 0, ';');

    $existentes = Insumo::query()->pluck('id', 'nombre');
    $siguiente = siguienteNumeroCodigo();
    $creados = [];
    $omitidos = [];

    try {
        DB::transaction(function () use ($handle, $existentes, &$siguiente, &$creados, &$omitidos): void {
            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                $nombre = trim((string) ($row[0] ?? ''));

                if ($nombre === '') {
                    continue;
                }

                if ($existentes->has($nombre)) {
                    $omitidos[] = $nombre;

                    continue;
                }

                $codigo = codigoInsumo($siguiente);
                $stock = trim((string) ($row[1] ?? '0'));
                $insumo = Insumo::create([
                    'codigo' => $codigo,
                    'nombre' => $nombre,
                    'unidad_medida_id' => UNIDAD_MEDIDA_ID,
                    'stock_minimo' => 0,
                    'stock_actual' => is_numeric($stock) ? (float) $stock : 0,
                    'precio_costo' => parsePrecioArgentino((string) ($row[2] ?? '')),
                    'fecha_ultimo_precio' => parseFechaCsv((string) ($row[3] ?? '')),
                    'activo' => true,
                ]);

                $insumo->categoriasInsumo()->syncWithoutDetaching([CATEGORIA_INSUMO_ID]);

                $existentes->put($nombre, $insumo->id);
                $creados[] = [
                    'id' => $insumo->id,
                    'codigo' => $codigo,
                    'nombre' => $nombre,
                ];
                $siguiente++;
            }
        });
    } finally {
        fclose($handle);
    }

    return [
        'creados' => $creados,
        'omitidos' => $omitidos,
    ];
}

if (PHP_SAPI === 'cli' && isset($argv[0]) && basename($argv[0]) === basename(__FILE__)) {
    importarMelaminasBootstrap();

    $csvPath = $argv[1] ?? dirname(__DIR__).DIRECTORY_SEPARATOR.'melaminas.csv';

    try {
        $resultado = importarMelaminas($csvPath);
        $creados = $resultado['creados'];
        $omitidos = $resultado['omitidos'];
        $primero = $creados[0]['codigo'] ?? null;
        $ultimo = $creados === [] ? null : $creados[array_key_last($creados)]['codigo'];

        echo 'Importación completada.'.PHP_EOL;
        echo 'Creados: '.count($creados).PHP_EOL;
        echo 'Omitidos (ya existían): '.count($omitidos).PHP_EOL;

        if ($primero !== null) {
            echo "Códigos: {$primero} → {$ultimo}".PHP_EOL;
        }

        foreach ($creados as $fila) {
            echo "  [{$fila['codigo']}] {$fila['nombre']} (id {$fila['id']})".PHP_EOL;
        }

        exit(0);
    } catch (Throwable $e) {
        fwrite(STDERR, $e->getMessage().PHP_EOL);
        exit(1);
    }
}
