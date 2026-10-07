<table>
    {{-- ── ENCABEZADO GENERAL ─────────────────────────────────────── --}}
    <tr>
        <td colspan="10" style="font-weight: bold; font-size: 16px; background-color: #1E3A8A; color: #FFFFFF;">
            PRESUPUESTO — {{ $presupuesto->codigo }}
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Versión</td>
        <td colspan="9">v{{ $presupuesto->version }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Estado</td>
        <td colspan="9">{{ \App\Models\Presupuesto::ESTADOS[$presupuesto->estado] ?? $presupuesto->estado }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Proyecto</td>
        <td colspan="9">{{ $presupuesto->proyecto->codigo_interno ?? '—' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Agencia</td>
        <td colspan="9">{{ ($presupuesto->agencia ?? $presupuesto->proyecto?->agencia)?->nombre ?? '—' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Responsable</td>
        <td colspan="9">{{ $presupuesto->responsable->name ?? '—' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Fecha Emisión</td>
        <td colspan="9">{{ $presupuesto->fecha_emision->format('d/m/Y') }}</td>
    </tr>
    @if($presupuesto->fecha_vencimiento)
    <tr>
        <td style="font-weight: bold;">Vencimiento</td>
        <td colspan="9">{{ $presupuesto->fecha_vencimiento->format('d/m/Y') }}</td>
    </tr>
    @endif
    @if($presupuesto->observaciones)
    <tr>
        <td style="font-weight: bold;">Observaciones</td>
        <td colspan="9">{{ $presupuesto->observaciones }}</td>
    </tr>
    @endif

    {{-- ── FILA VACÍA ─────────────────────────────────────────────── --}}
    <tr><td colspan="10"></td></tr>

    {{-- ── CABECERA DE ITEMS ──────────────────────────────────────── --}}
    <tr>
        <th style="font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; border: 1px solid #FFFFFF;">#</th>
        <th style="font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; border: 1px solid #FFFFFF;">Código</th>
        <th style="font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; border: 1px solid #FFFFFF;">Mobiliario</th>
        <th style="font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; border: 1px solid #FFFFFF;">Categoría</th>
        <th style="font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; border: 1px solid #FFFFFF;">Cantidad</th>
        <th style="font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; border: 1px solid #FFFFFF;">Precio Unit. $</th>
        <th style="font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; border: 1px solid #FFFFFF;">Costo Directo</th>
        <th style="font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; border: 1px solid #FFFFFF;">Subtotal $</th>
        <th style="font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; border: 1px solid #FFFFFF;">Descripción / Observaciones</th>
        <th style="font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; border: 1px solid #FFFFFF;">Notas</th>
    </tr>

    {{-- ── ITEMS ──────────────────────────────────────────────────── --}}
    @php $marcaPresupuestoId = $presupuesto->agencia?->proyecto?->marca?->id; @endphp
    @foreach($presupuesto->items as $i => $item)
    @php
        $entidad = $item->mobiliario ?? $item->insumo;
        $esSillaInsumo = $item->insumo?->esSilla() ?? false;
        $categoria = $item->mobiliario?->categoria?->nombre ?? ($item->insumo && ! $esSillaInsumo ? 'Silla' : '');
        $descripcion = $item->descripcion_override
            ?: ($item->mobiliario?->descripcion ?? $item->insumo?->descripcion ?? '');
        $insumoDisplay = $item->insumo?->nombreYCodigoParaMarca($marcaPresupuestoId);
        $nombreItem = $item->mobiliario?->nombre ?? $insumoDisplay['nombre'] ?? '—';
        $codigoItem = $item->mobiliario?->codigo_interno ?? $insumoDisplay['codigo'] ?? '';
        $precioItem = $item->precioUnitarioEfectivo();
        $costoDirectoItem = $item->costoDirectoUnitario();
        $subtotalItem = $item->subtotalEfectivo();
    @endphp
    <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $codigoItem }}</td>
        <td>{{ $nombreItem }}</td>
        <td>{{ $categoria }}</td>
        <td>{{ $item->cantidad }}</td>
        <td>{{ $precioItem ?? '' }}</td>
        <td>{{ $costoDirectoItem ?? '' }}</td>
        <td>{{ $subtotalItem ?? '' }}</td>
        <td>{{ $descripcion }}{{ $item->observaciones ? ' | Obs: ' . $item->observaciones : '' }}</td>
        <td>{{ $item->notas_manuales }}</td>
    </tr>
    @endforeach

    @php
        $incluyeLogistica = (bool) $presupuesto->logistica_instalacion_propia;
        $costoLogistica = $incluyeLogistica ? $presupuesto->logistica_costo_numerico : 0;
        $leyendaLogistica = $presupuesto->leyenda_logistica_efectiva;
        $indiceExtra = $presupuesto->items->count();
        $incluyeArmado = (bool) $presupuesto->armado;
        $costoArmado = $incluyeArmado ? $presupuesto->armado_costo_numerico : 0;
    @endphp
    @if($incluyeLogistica)
    <tr>
        <td>{{ ++$indiceExtra }}</td>
        <td></td>
        <td>{{ $leyendaLogistica }}</td>
        <td></td>
        <td>1</td>
        <td>{{ $costoLogistica > 0 ? $costoLogistica : '' }}</td>
        <td></td>
        <td>{{ $costoLogistica > 0 ? $costoLogistica : '' }}</td>
        <td></td>
        <td></td>
    </tr>
    @endif
    @if($incluyeArmado)
    <tr>
        <td>{{ ++$indiceExtra }}</td>
        <td></td>
        <td>{{ \App\Models\Presupuesto::LEYENDA_ARMADO }}</td>
        <td></td>
        <td>1</td>
        <td>{{ $costoArmado > 0 ? $costoArmado : '' }}</td>
        <td></td>
        <td>{{ $costoArmado > 0 ? $costoArmado : '' }}</td>
        <td></td>
        <td></td>
    </tr>
    @endif

    {{-- ── TOTAL ──────────────────────────────────────────────────── --}}
    @php $total = $presupuesto->items->sum(fn($i) => $i->subtotalEfectivo() ?? 0) + $costoLogistica + $costoArmado; @endphp
    @if($total > 0)
    <tr>
        <td colspan="7" style="font-weight: bold; text-align: right;">Total estimado:</td>
        <td style="font-weight: bold;">{{ number_format($total, 2) }}</td>
        <td colspan="2"></td>
    </tr>
    @endif
</table>
