<table>
    {{-- ── ENCABEZADO ─────────────────────────────────────────────── --}}
    <tr>
        <td colspan="6" style="font-weight: bold; font-size: 14px; background-color: #7C3AED; color: #FFFFFF;">
            HOJA DE PRODUCCIÓN — {{ $presupuesto->codigo }}
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Versión</td>
        <td colspan="5">v{{ $presupuesto->version }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Estado</td>
        <td colspan="5">{{ \App\Models\Presupuesto::ESTADOS[$presupuesto->estado] ?? $presupuesto->estado }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Marca</td>
        <td colspan="5">{{ $marca?->nombre ?? '—' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Proyecto</td>
        <td colspan="5">{{ $proyecto?->codigo_interno ?? '—' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Agencia</td>
        <td colspan="5">{{ $agencia?->nombre ?? '—' }}</td>
    </tr>
  @if($agencia?->direccion)
    <tr>
        <td style="font-weight: bold;">Dirección</td>
        <td colspan="5">{{ $agencia->direccion }}</td>
    </tr>
  @endif
  @if($agencia?->ciudad || $agencia?->provincia)
    <tr>
        <td style="font-weight: bold;">Ubicación</td>
        <td colspan="5">
            {{ $agencia->ciudad?->nombre }}@if($agencia?->ciudad && $agencia?->provincia), @endif{{ $agencia->provincia?->nombre }}
        </td>
    </tr>
  @endif
    <tr>
        <td style="font-weight: bold;">Responsable</td>
        <td colspan="5">{{ $presupuesto->responsable?->name ?? '—' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Fecha de emisión</td>
        <td colspan="5">{{ $presupuesto->fecha_emision->format('d/m/Y') }}</td>
    </tr>
  @if($presupuesto->observaciones)
    <tr>
        <td style="font-weight: bold;">Observaciones</td>
        <td colspan="5">{{ $presupuesto->observaciones }}</td>
    </tr>
  @endif

    <tr><td colspan="6"></td></tr>

    {{-- ── ITEMS POR SECTOR ───────────────────────────────────────── --}}
@php $globalIndex = 1; @endphp
@foreach($itemsPorSector as $sectorNombre => $sectorItems)
@php
    $esSinSector = ($sectorNombre === '__sin_sector__');
    $labelSector = $esSinSector ? 'Sin sector asignado' : $sectorNombre;
    $sectorBg    = $esSinSector ? '#6B7280' : '#3d68db';
@endphp
    <tr>
        <td colspan="6" style="font-weight: bold; font-size: 11px; background-color: {{ $sectorBg }}; color: #FFFFFF; text-transform: uppercase;">
            {{ $labelSector }}
        </td>
    </tr>
    <tr>
        <th style="font-weight: bold; background-color: rgba(61,104,219,0.5); color: #000000; border: 1px solid #9CA3AF;">#</th>
        <th style="font-weight: bold; background-color: rgba(61,104,219,0.5); color: #000000; border: 1px solid #9CA3AF;">Código</th>
        <th style="font-weight: bold; background-color: rgba(61,104,219,0.5); color: #000000; border: 1px solid #9CA3AF;">Mobiliario</th>
        <th style="font-weight: bold; background-color: rgba(61,104,219,0.5); color: #000000; border: 1px solid #9CA3AF;">Categoría</th>
        <th style="font-weight: bold; background-color: rgba(61,104,219,0.5); color: #000000; border: 1px solid #9CA3AF;">Descripción / Observaciones</th>
        <th style="font-weight: bold; background-color: rgba(61,104,219,0.5); color: #000000; border: 1px solid #9CA3AF;">Cant.</th>
    </tr>
    @foreach($sectorItems as $itemData)
    @php
        $item          = $itemData['item'];
        $mob           = $itemData['mobiliario'];
        $insumo        = $itemData['insumo'];
        $nombreItem    = $mob?->nombre ?? $insumo?->nombre ?? '—';
        $codigoItem    = $mob?->codigo_interno ?? $insumo?->codigo ?? '';
        $categoriaItem = $mob?->categoria?->nombre ?? ($insumo ? 'Insumo' : '');
        $desc          = $item->descripcion_override ?: $mob?->descripcion;
        $descObs       = $desc ?? '';
        if ($item->observaciones) {
            $descObs .= ($descObs ? ' | ' : '') . 'Obs: ' . $item->observaciones;
        }
    @endphp
    <tr>
        <td style="border: 1px solid #D1D5DB;">{{ $globalIndex++ }}</td>
        <td style="border: 1px solid #D1D5DB;">{{ $codigoItem }}</td>
        <td style="border: 1px solid #D1D5DB;">{{ $nombreItem }}</td>
        <td style="border: 1px solid #D1D5DB;">{{ $categoriaItem }}</td>
        <td style="border: 1px solid #D1D5DB;">{{ $descObs }}</td>
        <td style="border: 1px solid #D1D5DB; font-weight: bold;">{{ $item->cantidad }}</td>
    </tr>
    @endforeach
    <tr><td colspan="6"></td></tr>
@endforeach
</table>
