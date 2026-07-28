<?php

namespace App\Imports;

use App\Models\Insumo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InsumosStockImport implements ToCollection, WithHeadingRow
{
    public int $actualizados = 0;

    public int $omitidos = 0;

    /** @var array<int, string> */
    public array $errores = [];

    /** @var array<int, array<string, mixed>> */
    private array $registrosLog = [];

    /** @var array<int, string> */
    private array $columnasDetectadas = [];

    private ?string $archivoOrigen = null;

    private ?string $columnaCantidadUsada = null;

    public function setArchivoOrigen(?string $archivoOrigen): self
    {
        $this->archivoOrigen = $archivoOrigen;

        return $this;
    }

    public function collection(Collection $rows): void
    {
        if ($rows->isNotEmpty()) {
            $this->columnasDetectadas = array_keys($rows->first()->toArray());
        }

        foreach ($rows as $index => $row) {
            $linea = $index + 2;

            $codigo = $this->resolverCodigo($row);

            if ($codigo === '') {
                $this->registrarOmitido($linea, '—', 'Código vacío');

                continue;
            }

            [$cantidadRaw, $columnaCantidad] = $this->resolverCantidad($row);

            if ($this->columnaCantidadUsada === null && $columnaCantidad !== null) {
                $this->columnaCantidadUsada = $columnaCantidad;
            }

            if ($this->cantidadEstaVacia($cantidadRaw)) {
                $cantidad = 0.0;
            } elseif (! is_numeric($cantidadRaw)) {
                $this->registrarOmitido($linea, $codigo, 'Cantidad no numérica: ' . $this->formatearValor($cantidadRaw));

                continue;
            } else {
                $cantidad = max(0, (float) $cantidadRaw);
            }

            $insumo = Insumo::where('codigo', $codigo)->first();

            if (! $insumo) {
                $this->registrarOmitido($linea, $codigo, "Insumo '{$codigo}' no encontrado");

                continue;
            }

            $stockAnterior = (float) $insumo->stock_actual;

            $insumo->update(['stock_actual' => $cantidad]);

            $this->registrarActualizado(
                $linea,
                $codigo,
                $insumo->nombre,
                $stockAnterior,
                $cantidad,
                $cantidadRaw,
            );
        }
    }

    public function getResumenMensaje(): string
    {
        $mensaje = "{$this->actualizados} insumo(s) actualizado(s), {$this->omitidos} fila(s) omitida(s).";

        if ($this->columnaCantidadUsada) {
            $mensaje .= " Columna de cantidad: {$this->columnaCantidadUsada}.";
        }

        if ($this->errores === []) {
            return $mensaje;
        }

        $detalle = implode(' ', array_slice($this->errores, 0, 3));

        if (count($this->errores) > 3) {
            $detalle .= ' …';
        }

        return $mensaje . ' ' . $detalle;
    }

    public function guardarLog(): string
    {
        $nombreArchivo = 'import_' . now()->format('Y-m-d_His') . '.log';
        $rutaRelativa = 'logs/imports/insumos-stock/' . $nombreArchivo;

        $lineas = [
            '=== Importación masiva de stock — Insumos ===',
            'Fecha: ' . now()->format('d/m/Y H:i:s'),
            'Usuario: ' . (Auth::user()?->name ?? 'Sistema'),
            'Archivo origen: ' . ($this->archivoOrigen ?? '—'),
            'Columnas detectadas: ' . ($this->columnasDetectadas === [] ? '—' : implode(', ', $this->columnasDetectadas)),
            'Columna cantidad usada: ' . ($this->columnaCantidadUsada ?? 'ninguna'),
            'Resumen: ' . $this->getResumenMensaje(),
            str_repeat('-', 72),
            '',
        ];

        foreach ($this->registrosLog as $registro) {
            if ($registro['accion'] === 'actualizado') {
                $lineas[] = sprintf(
                    '[ACTUALIZADO] Fila %d | %s | %s | stock %s → %s | cantidad importada: %s',
                    $registro['linea'],
                    $registro['codigo'],
                    $registro['nombre'],
                    $this->formatearNumero($registro['stock_anterior']),
                    $this->formatearNumero($registro['stock_nuevo']),
                    $this->formatearValorImportado($registro['cantidad_raw']),
                );
            } else {
                $lineas[] = sprintf(
                    '[OMITIDO] Fila %d | %s | %s',
                    $registro['linea'],
                    $registro['codigo'],
                    $registro['motivo'],
                );
            }
        }

        if ($this->registrosLog === []) {
            $lineas[] = 'Sin registros procesados.';
        }

        $lineas[] = '';
        $lineas[] = str_repeat('-', 72);
        $lineas[] = 'Fin del log';

        Storage::disk('local')->put($rutaRelativa, implode(PHP_EOL, $lineas));

        return $rutaRelativa;
    }

    private function resolverCodigo(Collection $row): string
    {
        $valor = $this->resolverColumna($row, [
            'codigo',
            'codigo_insumo',
            'code',
        ]);

        return trim((string) ($valor ?? ''));
    }

    /**
     * @return array{0: mixed, 1: ?string}
     */
    private function resolverCantidad(Collection $row): array
    {
        $aliases = [
            'cantidad',
            'stock_actual',
            'stock',
            'qty',
            'quantity',
        ];

        foreach ($aliases as $alias) {
            $valor = $this->resolverColumna($row, [$alias]);

            if (! $this->cantidadEstaVacia($valor)) {
                return [$valor, $alias];
            }
        }

        // Fallback: segunda columna en archivos simples Codigo + Cantidad/Stock
        $values = array_values($row->toArray());

        if (count($values) >= 2 && ! $this->cantidadEstaVacia($values[1])) {
            return [$values[1], 'columna_2'];
        }

        return [null, null];
    }

    private function resolverColumna(Collection $row, array $aliases): mixed
    {
        $rowArray = $row->toArray();

        foreach ($aliases as $alias) {
            if (array_key_exists($alias, $rowArray)) {
                return $rowArray[$alias];
            }
        }

        foreach ($rowArray as $key => $value) {
            $normalizada = $this->normalizarNombreColumna((string) $key);

            if (in_array($normalizada, $aliases, true)) {
                return $value;
            }
        }

        return null;
    }

    private function normalizarNombreColumna(string $nombre): string
    {
        $nombre = mb_strtolower(trim($nombre));
        $nombre = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ñ'],
            ['a', 'e', 'i', 'o', 'u', 'n'],
            $nombre,
        );

        return preg_replace('/[^a-z0-9]+/', '_', $nombre) ?? $nombre;
    }

    private function registrarActualizado(
        int $linea,
        string $codigo,
        string $nombre,
        float $stockAnterior,
        float $stockNuevo,
        mixed $cantidadRaw,
    ): void {
        $this->actualizados++;

        $this->registrosLog[] = [
            'accion'         => 'actualizado',
            'linea'          => $linea,
            'codigo'         => $codigo,
            'nombre'         => $nombre,
            'stock_anterior' => $stockAnterior,
            'stock_nuevo'    => $stockNuevo,
            'cantidad_raw'   => $cantidadRaw,
        ];
    }

    private function registrarOmitido(int $linea, string $codigo, string $motivo): void
    {
        $this->omitidos++;
        $this->errores[] = "Fila {$linea} ({$codigo}): {$motivo}.";

        $this->registrosLog[] = [
            'accion' => 'omitido',
            'linea'  => $linea,
            'codigo' => $codigo,
            'motivo' => $motivo,
        ];
    }

    private function cantidadEstaVacia(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }

        if (is_string($value) && trim($value) === '') {
            return true;
        }

        return false;
    }

    private function formatearNumero(float $valor): string
    {
        return rtrim(rtrim(number_format($valor, 2, '.', ''), '0'), '.');
    }

    private function formatearValor(mixed $value): string
    {
        if ($value === null) {
            return '(vacío)';
        }

        if (is_string($value) && trim($value) === '') {
            return '(vacío)';
        }

        return (string) $value;
    }

    private function formatearValorImportado(mixed $value): string
    {
        if ($this->cantidadEstaVacia($value)) {
            return '0 (vacío)';
        }

        return $this->formatearValor($value);
    }
}
