<?php

namespace App\Exports;

use App\Models\LoteProcesoExterno;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LotesProcesoExternoExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private Builder $query) {}

    public function query(): Builder
    {
        return $this->query->with(['etapas.tipoProceso']);
    }

    public function headings(): array
    {
        return [
            'Código',
            'Código Mobiliario',
            'Item',
            'Tipo',
            'Cantidad',
            'Estado',
            'Etapa actual',
            'Inicio',
            'Fin estimado',
        ];
    }

    /**
     * @param  LoteProcesoExterno  $lote
     */
    public function map($lote): array
    {
        return [
            $lote->codigo,
            $lote->entidad_tipo === 'mobiliario' ? $lote->entidad_codigo : null,
            $lote->entidad_nombre,
            match ($lote->entidad_tipo) {
                'insumo'     => 'Insumo',
                'mobiliario' => 'Mobiliario',
                default      => $lote->entidad_tipo,
            },
            $lote->cantidad,
            LoteProcesoExterno::ESTADOS[$lote->estado] ?? $lote->estado,
            $lote->etapa_actual?->tipoProceso?->nombre,
            $lote->fecha_inicio?->format('d/m/Y'),
            $lote->fecha_finalizacion_estimada?->format('d/m/Y'),
        ];
    }
}
