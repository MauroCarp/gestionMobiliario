<?php

namespace App\Imports;

use App\Models\Insumo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InsumosStockImport implements ToCollection, WithHeadingRow
{
    public int $actualizados = 0;

    public int $omitidos = 0;

    /** @var array<int, string> */
    public array $errores = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $linea = $index + 2;

            $codigo = trim((string) ($row['codigo'] ?? ''));

            if ($codigo === '') {
                $this->omitidos++;
                $this->errores[] = "Fila {$linea}: código vacío.";

                continue;
            }

            if (! array_key_exists('cantidad', $row->toArray())) {
                $this->omitidos++;
                $this->errores[] = "Fila {$linea} ({$codigo}): cantidad vacía.";

                continue;
            }

            $cantidadRaw = $row['cantidad'];

            if ($this->cantidadEstaVacia($cantidadRaw)) {
                $this->omitidos++;
                $this->errores[] = "Fila {$linea} ({$codigo}): cantidad vacía.";

                continue;
            }

            if (! is_numeric($cantidadRaw)) {
                $this->omitidos++;
                $this->errores[] = "Fila {$linea} ({$codigo}): cantidad no numérica.";

                continue;
            }

            $cantidad = (float) $cantidadRaw;

            if ($cantidad < 0) {
                $this->omitidos++;
                $this->errores[] = "Fila {$linea} ({$codigo}): cantidad negativa.";

                continue;
            }

            $insumo = Insumo::where('codigo', $codigo)->first();

            if (! $insumo) {
                $this->omitidos++;
                $this->errores[] = "Fila {$linea}: insumo '{$codigo}' no encontrado.";

                continue;
            }

            $insumo->update(['stock_actual' => $cantidad]);
            $this->actualizados++;
        }
    }

    public function getResumenMensaje(): string
    {
        $mensaje = "{$this->actualizados} insumo(s) actualizado(s), {$this->omitidos} fila(s) omitida(s).";

        if ($this->errores === []) {
            return $mensaje;
        }

        $detalle = implode(' ', array_slice($this->errores, 0, 3));

        if (count($this->errores) > 3) {
            $detalle .= ' …';
        }

        return $mensaje . ' ' . $detalle;
    }

    private function cantidadEstaVacia(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }

        if (is_string($value) && trim($value) === '') {
            return true;
        }

        // 0 y '0' son valores válidos para dejar stock en cero
        return false;
    }
}
