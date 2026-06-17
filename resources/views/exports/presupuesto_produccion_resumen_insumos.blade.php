<table>
    <tr>
        <td colspan="6" style="font-weight: bold; font-size: 14px; background-color: #4C1D95; color: #FFFFFF;">
            RESUMEN TOTAL DE INSUMOS NECESARIOS — {{ $presupuesto->codigo }}
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Agencia</td>
        <td colspan="5">{{ $agencia?->nombre ?? '—' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Fecha de emisión</td>
        <td colspan="5">{{ $presupuesto->fecha_emision->format('d/m/Y') }}</td>
    </tr>
    <tr><td colspan="6"></td></tr>

@if(count($resumenInsumos) > 0)
    <tr>
        <th style="font-weight: bold; background-color: #4C1D95; color: #FFFFFF; border: 1px solid #4C1D95;">Código</th>
        <th style="font-weight: bold; background-color: #4C1D95; color: #FFFFFF; border: 1px solid #4C1D95;">Insumo</th>
        <th style="font-weight: bold; background-color: #4C1D95; color: #FFFFFF; border: 1px solid #4C1D95;">Total requerido</th>
        <th style="font-weight: bold; background-color: #4C1D95; color: #FFFFFF; border: 1px solid #4C1D95;">Unidad</th>
        <th style="font-weight: bold; background-color: #4C1D95; color: #FFFFFF; border: 1px solid #4C1D95;">Stock actual</th>
        <th style="font-weight: bold; background-color: #4C1D95; color: #FFFFFF; border: 1px solid #4C1D95;">Stock disponible</th>
    </tr>
    @foreach(collect($resumenInsumos)->sortBy(fn ($r) => $r['insumo']?->nombre) as $idx => $row)
    @php
        $insumo = $row['insumo'];
        $bg     = $idx % 2 === 0 ? '#FFFFFF' : '#F5F3FF';
    @endphp
    <tr>
        <td style="border: 1px solid #E5E7EB; background-color: {{ $bg }}; color: #6B7280;">{{ $insumo?->codigo ?? '—' }}</td>
        <td style="border: 1px solid #E5E7EB; background-color: {{ $bg }};">{{ $insumo?->nombre ?? '—' }}</td>
        <td style="border: 1px solid #E5E7EB; background-color: {{ $bg }}; text-align: right; font-weight: bold;">{{ number_format($row['total'], 2, ',', '.') }}</td>
        <td style="border: 1px solid #E5E7EB; background-color: {{ $bg }}; color: #6B7280;">{{ $row['unidad'] }}</td>
        <td style="border: 1px solid #E5E7EB; background-color: {{ $bg }}; text-align: right;">{{ number_format($insumo?->stock_actual ?? 0, 2, ',', '.') }}</td>
        <td style="border: 1px solid #E5E7EB; background-color: {{ $bg }}; text-align: right;">{{ number_format($insumo?->stock_disponible ?? 0, 2, ',', '.') }}</td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="6" style="color: #9CA3AF; text-align: center;">
            No hay insumos para resumir en este presupuesto.
        </td>
    </tr>
@endif
</table>
