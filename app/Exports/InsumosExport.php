<?php

namespace App\Exports;

use App\Models\Insumo;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InsumosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private Builder $query) {}

    public function query(): Builder
    {
        return $this->query->with(['unidadMedida', 'categoriasInsumo']);
    }

    public function headings(): array
    {
        return [
            'Código',
            'Nombre',
            'Categoría',
            'Unidad',
            'Stock actual',
            'Cantidad comprometida',
            'Cantidad en compra',
            'Stock proyectado',
            'Precio costo',
            'Ubicación',
            'Activo',
        ];
    }

    /**
     * @param  Insumo  $insumo
     */
    public function map($insumo): array
    {
        return [
            $insumo->codigo,
            $insumo->nombre,
            $insumo->categoriasInsumo->pluck('nombre')->join(', '),
            $insumo->unidadMedida?->nombre,
            $insumo->stock_actual,
            data_get($insumo, 'stock_comprometido'),
            data_get($insumo, 'pendiente_recepcion'),
            data_get($insumo, 'stock_proyectado'),
            $insumo->precio_costo,
            $insumo->ubicacion,
            $insumo->activo ? 'Sí' : 'No',
        ];
    }
}
