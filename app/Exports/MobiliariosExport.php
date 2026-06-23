<?php

namespace App\Exports;

use App\Models\Mobiliario;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MobiliariosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private Builder $query) {}

    public function query(): Builder
    {
        return $this->query->with(['categoria', 'marca', 'atributos']);
    }

    public function headings(): array
    {
        return [
            'Código',
            'Nombre',
            'Categoría',
            'Marca',
            'Atributos',
            'Estado',
            'Versión',
            'Precio',
            'Descripción',
            'Observaciones',
        ];
    }

    /**
     * @param  Mobiliario  $mobiliario
     */
    public function map($mobiliario): array
    {
        return [
            $mobiliario->codigo_interno,
            $mobiliario->nombre,
            $mobiliario->categoria?->nombre,
            $mobiliario->marca?->nombre,
            $mobiliario->atributos->isNotEmpty()
                ? $mobiliario->atributos->map(fn ($a) => $a->clave . ': ' . $a->valor)->join(' · ')
                : null,
            Mobiliario::ESTADOS[$mobiliario->estado] ?? $mobiliario->estado,
            $mobiliario->version_actual,
            $mobiliario->precio,
            $mobiliario->descripcion,
            $mobiliario->observaciones,
        ];
    }
}
