<table>
    <tr>
        <td colspan="5" style="font-weight: bold; font-size: 14px; background-color: #7C3AED; color: #FFFFFF;">
            DETALLE DE INSUMOS POR MOBILIARIO — {{ $presupuesto->codigo }}
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Agencia</td>
        <td colspan="4">{{ $agencia?->nombre ?? '—' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Fecha de emisión</td>
        <td colspan="4">{{ $presupuesto->fecha_emision->format('d/m/Y') }}</td>
    </tr>
    <tr><td colspan="5"></td></tr>

@forelse($itemsConMobiliario as $itemData)
@php
    $item        = $itemData['item'];
    $mob         = $itemData['mobiliario'];
    $composicion = $mob->composicionTecnica;
    $cantItem    = (int) $item->cantidad;
    $sectorNombre = $itemData['sector']?->nombre;
@endphp
    <tr>
        <td colspan="5" style="font-weight: bold; background-color: #EDE9FE; border-left: 4px solid #7C3AED; color: #4C1D95;">
            {{ $mob->nombre }}
            — Cód: {{ $mob->codigo_interno }}
            | Cantidad en presupuesto: {{ $cantItem }}
            @if($sectorNombre)
                | Sector: {{ $sectorNombre }}
            @endif
        </td>
    </tr>

    @if($composicion->isEmpty())
    <tr>
        <td colspan="5" style="color: #9CA3AF; border: 1px solid #E5E7EB;">
            Este mobiliario no tiene composición técnica registrada.
        </td>
    </tr>
    @else
    <tr>
        <th style="font-weight: bold; background-color: #DDD6FE; color: #4C1D95; border: 1px solid #C4B5FD;">Código</th>
        <th style="font-weight: bold; background-color: #DDD6FE; color: #4C1D95; border: 1px solid #C4B5FD;">Insumo</th>
        <th style="font-weight: bold; background-color: #DDD6FE; color: #4C1D95; border: 1px solid #C4B5FD;">Cant. unit.</th>
        <th style="font-weight: bold; background-color: #DDD6FE; color: #4C1D95; border: 1px solid #C4B5FD;">Unidad</th>
        <th style="font-weight: bold; background-color: #DDD6FE; color: #4C1D95; border: 1px solid #C4B5FD;">Total (× {{ $cantItem }})</th>
    </tr>
        @foreach($composicion as $comp)
        @php
            $insumoComp = $comp->insumo;
            $cantUnit   = (float) $comp->cantidad;
            $cantTotal  = $cantUnit * $cantItem;
            $unidad     = $insumoComp?->unidadMedida?->nombre ?? '—';
        @endphp
    <tr>
        <td style="border: 1px solid #E5E7EB; color: #6B7280;">{{ $insumoComp?->codigo ?? '—' }}</td>
        <td style="border: 1px solid #E5E7EB;">{{ $insumoComp?->nombre ?? '—' }}</td>
        <td style="border: 1px solid #E5E7EB; text-align: right;">{{ number_format($cantUnit, 2, ',', '.') }}</td>
        <td style="border: 1px solid #E5E7EB; text-align: center; color: #6B7280;">{{ $unidad }}</td>
        <td style="border: 1px solid #E5E7EB; text-align: right; font-weight: bold; color: #4C1D95;">{{ number_format($cantTotal, 2, ',', '.') }}</td>
    </tr>
        @endforeach
    @endif
    <tr><td colspan="5"></td></tr>
@empty
    <tr>
        <td colspan="5" style="color: #9CA3AF; text-align: center;">
            No hay mobiliarios con composición técnica en este presupuesto.
        </td>
    </tr>
@endforelse
</table>
