<?php

namespace App\Services;

use App\Models\Insumo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ActualizarPreciosInsumosService
{
    public const CRITERIO_CATEGORIA = 'categoria';

    public const CRITERIO_PROVEEDOR = 'proveedor';

    public const CRITERIO_FECHA = 'fecha';

    public function calcularNuevoPrecio(?float $precioCosto, float $porcentaje): ?float
    {
        if ($precioCosto === null) {
            return null;
        }

        return round($precioCosto * (1 + $porcentaje / 100), 2);
    }

    public function fechaActualizacion(Carbon|string|null $fecha = null): string
    {
        return Carbon::parse($fecha ?? Carbon::now())->toDateString();
    }

    public function aplicarAInsumo(Insumo $insumo, float $porcentaje, Carbon|string|null $fecha = null): bool
    {
        $precioActual = $insumo->precio_costo === null
            ? null
            : (float) $insumo->precio_costo;

        $nuevoPrecio = $this->calcularNuevoPrecio($precioActual, $porcentaje);

        if ($nuevoPrecio === null) {
            return false;
        }

        $insumo->precio_costo = $nuevoPrecio;
        $insumo->fecha_ultimo_precio = $this->fechaActualizacion($fecha);

        return true;
    }

    public function actualizar(string $criterio, mixed $valor, float $porcentaje): int
    {
        $query = Insumo::query()->whereNotNull('precio_costo');
        $this->aplicarFiltro($query, $criterio, $valor);

        $fecha = $this->fechaActualizacion();
        $actualizados = 0;

        DB::transaction(function () use ($query, $porcentaje, $fecha, &$actualizados): void {
            $query->chunkById(100, function ($insumos) use ($porcentaje, $fecha, &$actualizados): void {
                foreach ($insumos as $insumo) {
                    if (! $this->aplicarAInsumo($insumo, $porcentaje, $fecha)) {
                        continue;
                    }

                    $insumo->save();
                    $actualizados++;
                }
            });
        });

        return $actualizados;
    }

    private function aplicarFiltro(Builder $query, string $criterio, mixed $valor): void
    {
        match ($criterio) {
            self::CRITERIO_CATEGORIA => $query->whereHas(
                'categoriasInsumo',
                fn (Builder $categorias) => $categorias->where('categorias_insumo.id', $valor)
            ),
            self::CRITERIO_PROVEEDOR => $query->where('proveedor_id', $valor),
            self::CRITERIO_FECHA => $this->filtrarPorMesAnio($query, (string) $valor),
            default => throw new InvalidArgumentException("Criterio de actualización inválido: {$criterio}"),
        };
    }

    private function filtrarPorMesAnio(Builder $query, string $mesAnio): void
    {
        $fecha = $this->parsearMesAnio($mesAnio);

        $query
            ->whereYear('fecha_ultimo_precio', $fecha->year)
            ->whereMonth('fecha_ultimo_precio', $fecha->month);
    }

    private function parsearMesAnio(string $mesAnio): Carbon
    {
        if (preg_match('/^(\d{4})-(\d{2})$/', $mesAnio, $partes) === 1) {
            return Carbon::create((int) $partes[1], (int) $partes[2], 1);
        }

        return Carbon::parse($mesAnio)->startOfMonth();
    }
}
