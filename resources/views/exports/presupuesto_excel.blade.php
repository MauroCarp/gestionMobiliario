@php
    $headerAzul = 'font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; border: 1px solid #FFFFFF;';
    $headerCosto = 'font-weight: bold; background-color: #0F766E; color: #FFFFFF; border: 1px solid #FFFFFF;';
    $headerMargen = 'font-weight: bold; background-color: #7C3AED; color: #FFFFFF; border: 1px solid #FFFFFF;';
    $fmt = fn (?float $valor): string => $valor === null ? '' : number_format($valor, 2, ',', '.');
@endphp
<table>
    {{-- ── ENCABEZADO GENERAL ─────────────────────────────────────── --}}
    <tr>
        <td colspan="12" style="font-weight: bold; font-size: 16px; background-color: #1E3A8A; color: #FFFFFF;">
            PRESUPUESTO — {{ $presupuesto->codigo }}
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Versión</td>
        <td colspan="11">v{{ $presupuesto->version }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Estado</td>
        <td colspan="11">{{ \App\Models\Presupuesto::ESTADOS[$presupuesto->estado] ?? $presupuesto->estado }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Proyecto</td>
        <td colspan="11">{{ $presupuesto->proyecto->codigo_interno ?? '—' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Agencia</td>
        <td colspan="11">{{ ($presupuesto->agencia ?? $presupuesto->proyecto?->agencia)?->nombre ?? '—' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Responsable</td>
        <td colspan="11">{{ $presupuesto->responsable->name ?? '—' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Fecha Emisión</td>
        <td colspan="11">{{ $presupuesto->fecha_emision->format('d/m/Y') }}</td>
    </tr>
    @if($presupuesto->fecha_vencimiento)
    <tr>
        <td style="font-weight: bold;">Vencimiento</td>
        <td colspan="11">{{ $presupuesto->fecha_vencimiento->format('d/m/Y') }}</td>
    </tr>
    @endif
    @if($presupuesto->observaciones)
    <tr>
        <td style="font-weight: bold;">Observaciones</td>
        <td colspan="11">{{ $presupuesto->observaciones }}</td>
    </tr>
    @endif

    {{-- ── FILA VACÍA ─────────────────────────────────────────────── --}}
    <tr><td colspan="12"></td></tr>

    {{-- ── CABECERA DE ITEMS ──────────────────────────────────────── --}}
    <tr>
        <th style="{{ $headerAzul }}">#</th>
        <th style="{{ $headerAzul }}">Código</th>
        <th style="{{ $headerAzul }}">Mobiliario</th>
        <th style="{{ $headerAzul }}">Categoría</th>
        <th style="{{ $headerAzul }}">Cantidad</th>
        <th style="{{ $headerAzul }}">Precio Unit. $</th>
        <th style="{{ $headerAzul }}">Subtotal $</th>
        <th style="{{ $headerCosto }}">Costo Directo Unitario</th>
        <th style="{{ $headerCosto }}">Costo Directo Total</th>
        <th style="{{ $headerMargen }}">Margen Bruto directo $</th>
        <th style="{{ $headerMargen }}">Margen bruto directo %</th>
        <th style="{{ $headerAzul }}">Notas</th>
    </tr>

    {{-- ── ITEMS ──────────────────────────────────────────────────── --}}
    @php $marcaPresupuestoId = $presupuesto->agencia?->proyecto?->marca?->id; @endphp
    @foreach($presupuesto->items as $i => $item)
    @php
        $esSillaInsumo = $item->insumo?->esSilla() ?? false;
        $categoria = $item->mobiliario?->categoria?->nombre ?? ($item->insumo && ! $esSillaInsumo ? 'Silla' : '');
        $insumoDisplay = $item->insumo?->nombreYCodigoParaMarca($marcaPresupuestoId);
        $nombreItem = $item->mobiliario?->nombre ?? $insumoDisplay['nombre'] ?? '—';
        $codigoItem = $item->mobiliario?->codigo_interno ?? $insumoDisplay['codigo'] ?? '';
        $precioItem = $item->precioUnitarioEfectivo();
        $subtotalItem = $item->subtotalEfectivo();
        $costoUnitarioItem = $item->costoDirectoUnitario();
        $costoTotalItem = $item->costoDirectoTotal();
        $margenItem = $item->margenBrutoDirecto();
        $margenPorcentajeItem = $item->margenBrutoDirectoPorcentaje();
    @endphp
    <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $codigoItem }}</td>
        <td>{{ $nombreItem }}</td>
        <td>{{ $categoria }}</td>
        <td>{{ number_format((int) $item->cantidad, 0, ',', '.') }}</td>
        <td>{{ $fmt($precioItem) }}</td>
        <td>{{ $fmt($subtotalItem) }}</td>
        <td>{{ $fmt($costoUnitarioItem) }}</td>
        <td>{{ $fmt($costoTotalItem) }}</td>
        <td>{{ $fmt($margenItem) }}</td>
        <td>{{ $fmt($margenPorcentajeItem) }}</td>
        <td>{{ $item->notas_manuales }}</td>
    </tr>
    @endforeach

    @php
        $indiceExtra = $presupuesto->items->count();
        $incluyeArmado = (bool) $presupuesto->armado;
        $costoArmado = $incluyeArmado ? $presupuesto->armado_costo_numerico : 0;
    @endphp
    @if($incluyeArmado)
    <tr>
        <td>{{ ++$indiceExtra }}</td>
        <td></td>
        <td>{{ \App\Models\Presupuesto::LEYENDA_ARMADO }}</td>
        <td></td>
        <td>1</td>
        <td>{{ $costoArmado > 0 ? $fmt($costoArmado) : '' }}</td>
        <td>{{ $costoArmado > 0 ? $fmt($costoArmado) : '' }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @endif

    {{-- ── TOTAL ──────────────────────────────────────────────────── --}}
    @php
        $total = $presupuesto->items->sum(fn ($i) => $i->subtotalEfectivo() ?? 0) + $costoArmado;
        $totalCostoDirecto = $presupuesto->items->sum(fn ($i) => $i->costoDirectoTotal() ?? 0);
        $totalMargen = $presupuesto->items->sum(fn ($i) => $i->margenBrutoDirecto() ?? 0);
        $margenPonderado = $presupuesto->margenBrutoDirectoPorcentajePonderado();
    @endphp
    @if($total > 0)
    <tr>
        <td colspan="6" style="font-weight: bold; text-align: right;">Total estimado:</td>
        <td style="font-weight: bold;">{{ $fmt($total) }}</td>
        <td></td>
        <td style="font-weight: bold;">{{ $fmt($totalCostoDirecto) }}</td>
        <td style="font-weight: bold;">{{ $fmt($totalMargen) }}</td>
        <td style="font-weight: bold;">{{ $fmt($margenPonderado) }}</td>
        <td></td>
    </tr>
    @endif
</table>
