<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Producción {{ $presupuesto->codigo }}</title>
    <style>
        * { box-sizing: border-box; }

        @page {
            margin-top: 5mm;
            margin-bottom: 5mm; /* espacio para el footer fijo */
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1F2937;
            margin: 0;
            /* padding: 15header-agency .agency-subpx 25px 20px; */
        }

        /* ── CABECERA ─────────────────────────────────────────── */
        .header-table { width: 100%; margin-bottom: 10px; }
        .header-marca { width: 150px; vertical-align: middle; }
        .header-agency { vertical-align: middle; padding: 0 14px; }
        .header-agency .agency-name { font-size: 14px; font-weight: bold; color: #1E3A8A; }
        .header-agency .agency-sub  { font-size: 12px; color: #6B7280; margin-top: 2px; }
        .header-empresa { width: 200px; text-align: right; vertical-align: middle; }
        .header-empresa .empresa-codigo {
            font-size: 10px; font-weight: bold;
            color: #374151; margin-top: 6px; text-align: right;
        }
        .header-divider { border: none; border-top: 3px solid #db0d0d; margin: 0 0 10px; }

        /* ── TÍTULO PRODUCCIÓN ────────────────────────────────── */
        .produccion-badge {
            background: #7C3AED;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 4px 12px;
            display: inline-block;
            margin-bottom: 8px;
        }

        /* ── FECHA DE EMISIÓN ─────────────────────────────────── */
        .fecha-row { text-align: right; font-size: 10px; margin-bottom: 12px; }
        .fecha-row .lbl { color: #6B7280; }
        .fecha-row .val { font-weight: bold; }

        /* ── TABLA DE ITEMS ───────────────────────────────────── */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 0; border: 1px solid #9CA3AF; }
        .items-table thead tr { background: rgba(61, 104, 219, 0.5); color: #FFFFFF; }
        .items-table thead th {
            padding: 7px 8px; text-align: left; font-size: 12px;
            text-transform: uppercase; letter-spacing: 0.4px;
            border: 1px solid rgba(61, 104, 219, 0.5);
        }
        .items-table thead th.center { text-align: center; }
        .items-table tbody td { padding: 7px 8px; vertical-align: top; font-size: 12px; border: 1px solid #D1D5DB; }
        .items-table tbody td.center { text-align: center; }

        /* ── SECTOR HEADER ───────────────────────────────────── */
        .sector-header {
            background: #3d68db;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 6px 10px;
            margin-top: 14px;
            margin-bottom: 0;
        }
        .sector-spacer { height: 14px; }

        .item-num    { color: #6B7280; font-size: 10px; font-weight: bold; }
        .item-img-cell { overflow: hidden; }
        .item-img    { width: 80px; height: auto; display: block; }
        .item-no-img {
            width: 80px; height: 70px;
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
            font-size: 8px; color: #9CA3AF;
            text-align: center; line-height: 70px;
        }
        .item-code  { font-size: 11px; color: #9CA3AF; margin-top: 2px; }
        .item-desc  { font-size: 14px; color: #000000; margin-top: 3px; line-height: 1.4; }
        .item-qty   { font-size: 13px; font-weight: bold; }

        /* ── SECCIÓN DETALLE INSUMOS ──────────────────────────── */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            background: #7C3AED;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 7px 12px;
            margin-top: 18px;
            margin-bottom: 0;
        }

        .mob-insumos-block { margin-top: 12px; page-break-inside: avoid; }
        .mob-insumos-title {
            background: #EDE9FE;
            border-left: 4px solid #7C3AED;
            padding: 5px 10px;
            font-size: 10px;
            font-weight: bold;
            color: #4C1D95;
            margin-bottom: 0;
        }
        .mob-insumos-subtitle {
            font-size: 12px;
            font-weight: normal;
            color: #6B7280;
        }

        .insumos-table { width: 100%; border-collapse: collapse; }
        .insumos-table thead tr { background: #DDD6FE; }
        .insumos-table thead th {
            padding: 5px 8px; text-align: left; font-size: 12px;
            text-transform: uppercase; letter-spacing: 0.3px;
            border: 1px solid #C4B5FD; color: #4C1D95;
        }
        .insumos-table thead th.right { text-align: right; }
        .insumos-table thead th.center { text-align: center; }
        .insumos-table tbody td { padding: 5px 8px; font-size: 12px; border: 1px solid #E5E7EB; vertical-align: middle; }
        .insumos-table tbody td.right  { text-align: right; }
        .insumos-table tbody td.center { text-align: center; }
        .insumos-table tfoot td {
            padding: 5px 8px; font-size: 12px; font-weight: bold;
            background: #F5F3FF; border: 1px solid #C4B5FD; color: #4C1D95;
        }

        .flujo-externo-title {
            background: #F5F3FF;
            border-left: 4px solid #7C3AED;
            padding: 5px 10px;
            font-size: 10px;
            font-weight: bold;
            color: #4C1D95;
            margin-top: 8px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        /* ── TABLA RESUMEN TOTAL INSUMOS ──────────────────────── */
        .resumen-table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        .resumen-table thead tr { background: #4C1D95; color: #fff; }
        .resumen-table thead th {
            padding: 6px 8px; text-align: left; font-size: 12px;
            text-transform: uppercase; letter-spacing: 0.4px;
            border: 1px solid #4C1D95;
        }
        .resumen-table thead th.right { text-align: right; }
        .resumen-table tbody td { padding: 5px 8px; font-size: 12px; border: 1px solid #E5E7EB; }
        .resumen-table tbody td.right  { text-align: right; font-weight: bold; }
        .resumen-table tbody tr.even   { background: #F5F3FF; }

        /* ── MARCA DE AGUA ───────────────────────────────────── */
        .watermark-wrapper {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1000; overflow: visible;
        }
        .watermark {
            position: absolute; top: 500px; left: 50%;
            width: 1000px; margin-left: -500px;
            opacity: 0.08; transform: rotate(-25deg);
        }

        /* ── PIE FIJO ────────────────────────────────────────── */
        .footer-fixed {
            position: fixed; bottom: -10px; left: 0; right: 0;
            border-top: 1px solid #E5E7EB; padding: 3px 25px;
            font-size: 12px; color: #9CA3AF;
        }
        .footer-fixed table { width: 100%; }

        /* page break */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

{{-- ── MARCA DE AGUA ───────────────────────────────────────────── --}}
@if(!empty($logoEmpresaBase64))
<div class="watermark-wrapper">
    <img src="{{ $logoEmpresaBase64 }}" class="watermark">
</div>
@endif

{{-- ── PIE FIJO ─────────────────────────────────────────────────── --}}
<div class="footer-fixed">
    <table cellpadding="0" cellspacing="0">
        <tr>
            <td>PRODUCCIÓN — {{ $presupuesto->codigo }}</td>
            <td style="text-align:right;">Generado el {{ now()->format('d/m/Y H:i') }}</td>
        </tr>
    </table>
</div>

{{-- ── CABECERA ────────────────────────────────────────────────── --}}
<table class="header-table" cellpadding="0" cellspacing="0">
    <tr>
        <td class="header-marca">
            @if(!empty($logoBase64))
                <img src="{{ $logoBase64 }}" style="max-width:140px; max-height:90px;">
            @elseif($marca)
                <div style="width:140px; height:70px; background:#E5E7EB; border:1px solid #D1D5DB;
                            text-align:center; line-height:70px; font-size:9px; color:#9CA3AF;">
                    {{ $marca->nombre }}
                </div>
            @endif
        </td>
        <td class="header-agency">
            <div class="agency-name">{{ $agencia->nombre ?? 'Sin agencia' }}</div>
            @if($agencia?->direccion)
                <div class="agency-sub">{{ $agencia->direccion }}</div>
            @endif
            @if($agencia?->ciudad || $agencia?->provincia)
                <div class="agency-sub">
                    {{ $agencia->ciudad?->nombre }}@if($agencia?->ciudad && $agencia?->provincia), @endif{{ $agencia->provincia?->nombre }}
                </div>
            @endif
            @if($agencia?->responsable)
                <div class="agency-sub">Resp.: {{ $agencia->responsable }}</div>
            @endif
        </td>
        <td class="header-empresa">
            @if(!empty($logoEmpresaBase64))
                <img src="{{ $logoEmpresaBase64 }}" style="height:65px; width:auto;">
            @else
                <div style="width:190px; height:65px; background:#E5E7EB; border:1px solid #D1D5DB;
                            text-align:center; line-height:65px; font-size:9px; color:#9CA3AF;">
                    LOGO EMPRESA
                </div>
            @endif
            <div class="empresa-codigo">Presupuesto: {{ $presupuesto->codigo }}</div>
        </td>
    </tr>
</table>

<hr class="header-divider">

<div><span class="produccion-badge">&#x2699; Hoja de Producción</span></div>

{{-- ── FECHA ─────────────────────────────────────────────────────── --}}
<div class="fecha-row">
    <span class="lbl">Fecha de emisión: </span>
    <span class="val">{{ $presupuesto->fecha_emision->format('d/m/Y') }}</span>
</div>

{{-- ══════════════════════════════════════════════════════════════
     SECCIÓN 1 — RESUMEN DE ITEMS (mismo listado que el presupuesto)
     ══════════════════════════════════════════════════════════════ --}}
@php $globalIndex = 1; @endphp

@foreach($itemsPorSector as $sectorNombre => $sectorItems)
@php
    $esSinSector = ($sectorNombre === '__sin_sector__');
    $labelSector = $esSinSector ? 'Sin sector asignado' : $sectorNombre;
@endphp

<div class="sector-header" style="{{ $esSinSector ? 'background:#6B7280;' : '' }}">
    {{ $labelSector }}
</div>

<table class="items-table" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th style="width:18px;" class="center">#</th>
            <th style="width:90px;" class="center">Imagen</th>
            <th style="width:20%;">Mobiliario</th>
            <th>Descripción / Observaciones</th>
            <th style="width:36px;" class="center">Cant.</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sectorItems as $idx => $itemData)
        @php
            $item          = $itemData['item'];
            $mob           = $itemData['mobiliario'];
            $insumo        = $itemData['insumo'];
            $imgBase64     = $itemData['imagen_base64'];
            $nombreItem    = $mob?->nombre ?? $insumo?->nombre ?? '—';
            $codigoItem    = $mob?->codigo_interno ?? $insumo?->codigo ?? '';
            $categoriaItem = $mob?->categoria?->nombre ?? ($insumo ? 'Insumo' : '');
        @endphp
        <tr>
            <td class="center"><span class="item-num">{{ $globalIndex++ }}</span></td>
            <td class="center item-img-cell">
                @if($imgBase64)
                    <img src="{{ $imgBase64 }}" class="item-img">
                @else
                    <div class="item-no-img">Sin imagen</div>
                @endif
            </td>
            <td>
                <strong>{{ $nombreItem }}</strong>
                <div class="item-code" style="font-size: 14px;font-weight: bold;">Cód: {{ $codigoItem }}</div>
                @if($categoriaItem)
                    <div class="item-code">{{ $categoriaItem }}</div>
                @endif
            </td>
            <td>
                @php $desc = $item->descripcion_override ?: $mob?->descripcion; @endphp
                @if($desc)
                    <div class="item-desc">{{ $desc }}</div>
                @endif
                @if($item->observaciones)
                    <div class="item-desc"><strong>Obs:</strong> {{ $item->observaciones }}</div>
                @endif
            </td>
            <td class="center"><span class="item-qty">{{ $item->cantidad }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>

@endforeach


{{-- ══════════════════════════════════════════════════════════════
     SECCIÓN 2 — DETALLE DE INSUMOS POR MOBILIARIO
     ══════════════════════════════════════════════════════════════ --}}
<div class="page-break"></div>

<div class="section-title">Detalle de Insumos por Mobiliario</div>

@php
    // Agrupar items que tienen mobiliario (ignorar insumos directos como sillas)
    $itemsConMobiliario = $items->filter(fn($d) => $d['mobiliario'] !== null);

    // Acumular totales de insumos para el resumen final (array nativo: Collection no permite += anidado)
    $resumenInsumos = []; // insumo_id => ['insumo' => ..., 'total' => float]
@endphp

@forelse($itemsConMobiliario as $itemData)
@php
    $item        = $itemData['item'];
    $mob         = $itemData['mobiliario'];
    $imgBase64   = $itemData['imagen_base64'];
    $composicion = $mob->composicionTecnica;
    $cantItem    = (int) $item->cantidad;
    $plantilla   = $mob->plantillaFlujos->first();
    $etapasFlujo = $plantilla?->etapas ?? collect();
@endphp

<div class="mob-insumos-block">
    <div class="mob-insumos-title">
        {{ $mob->nombre }}
        <span class="mob-insumos-subtitle">
            &nbsp;—&nbsp;Cód: {{ $mob->codigo_interno }}
            &nbsp;|&nbsp;Cantidad en presupuesto: <strong>{{ $cantItem }}</strong>
            @if($itemData['sector'])
                &nbsp;|&nbsp;Sector: {{ $itemData['sector']->nombre }}
            @endif
        </span>
    </div>

    @if($composicion->isEmpty())
        <div style="padding:6px 10px; font-size:12px; color:#9CA3AF; border:1px solid #E5E7EB; border-top:none;">
            Este mobiliario no tiene composición técnica registrada.
        </div>
    @else
        <table class="insumos-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width:80px;">Código</th>
                    <th>Insumo</th>
                    <th style="width:80px;" class="right">Cant. unit.</th>
                    <th style="width:60px;" class="center">Unidad</th>
                    <th style="width:90px;" class="right">Total (× {{ $cantItem }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach($composicion as $comp)
                @php
                    $insumoComp  = $comp->insumo;
                    $cantUnit    = (float) $comp->cantidad;
                    $cantTotal   = $cantUnit * $cantItem;
                    $unidad      = $insumoComp?->unidadMedida?->nombre ?? '—';

                    // Acumular en resumen
                    $insId = $insumoComp?->id;
                    if ($insId) {
                        if (isset($resumenInsumos[$insId])) {
                            $resumenInsumos[$insId]['total'] += $cantTotal;
                        } else {
                            $resumenInsumos[$insId] = [
                                'insumo'  => $insumoComp,
                                'unidad'  => $unidad,
                                'total'   => $cantTotal,
                            ];
                        }
                    }
                @endphp
                <tr>
                    <td style="font-size:12px; color:#6B7280;">{{ $insumoComp?->codigo ?? '—' }}</td>
                    <td>{{ $insumoComp?->nombre ?? '—' }}</td>
                    <td class="right">{{ number_format($cantUnit, 2, ',', '.') }}</td>
                    <td class="center" style="font-size:9px; color:#6B7280;">{{ $unidad }}</td>
                    <td class="right" style="font-weight:bold; color:#4C1D95;">{{ number_format($cantTotal, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            @if($comp && $composicion->count() > 1)
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right;">Total insumos para {{ $cantItem }} unidades:</td>
                    <td style="text-align:right; color:#4C1D95;">{{ $composicion->count() }} tipos</td>
                </tr>
            </tfoot>
            @endif
        </table>
    @endif

    <div class="flujo-externo-title">
        Flujo externo
        @if($plantilla)
            — {{ $plantilla->nombre }}
        @endif
    </div>

    @if(!$plantilla || $etapasFlujo->isEmpty())
        <div style="padding:6px 10px; font-size:12px; color:#9CA3AF; border:1px solid #E5E7EB; border-top:none;">
            Este mobiliario no tiene una plantilla de flujo externo activa configurada.
        </div>
    @else
        <table class="insumos-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width:50px;" class="center">Orden</th>
                    <th>Proceso</th>
                    <th style="width:30%;">Tercero</th>
                    <th style="width:70px;" class="center">Días est.</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($etapasFlujo as $etapa)
                <tr>
                    <td class="center">{{ $etapa->orden }}</td>
                    <td>{{ $etapa->tipoProceso?->nombre ?? '—' }}</td>
                    <td>{{ $etapa->tercero?->nombre ?? 'Sin asignar' }}</td>
                    <td class="center">{{ $etapa->dias_estimados ?? '—' }}</td>
                    <td style="font-size:11px; color:#6B7280;">{{ $etapa->observaciones ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@empty
    <div style="padding:12px; font-size:10px; color:#9CA3AF; text-align:center; margin-top:10px;">
        No hay mobiliarios con composición técnica en este presupuesto.
    </div>
@endforelse

{{-- ══════════════════════════════════════════════════════════════
     SECCIÓN 3 — RESUMEN TOTAL DE INSUMOS NECESARIOS
     ══════════════════════════════════════════════════════════════ --}}
@if(count($resumenInsumos) > 0)
<div class="page-break"></div>

<div class="section-title">Resumen Total de Insumos Necesarios</div>

<table class="resumen-table" cellpadding="0" cellspacing="0" style="margin-top: 10px;">
    <thead>
        <tr>
            <th style="width:90px;">Código</th>
            <th>Insumo</th>
            <th style="width:70px;" class="right">Total</th>
            <th style="width:70px;">Unidad</th>
        </tr>
    </thead>
    <tbody>
        @foreach(collect($resumenInsumos)->sortBy(fn($r) => $r['insumo']?->nombre) as $idx => $row)
        <tr class="{{ $idx % 2 === 0 ? '' : 'even' }}">
            <td style="font-size:12px; color:#6B7280;">{{ $row['insumo']?->codigo ?? '—' }}</td>
            <td>{{ $row['insumo']?->nombre ?? '—' }}</td>
            <td class="right">{{ number_format($row['total'], 2, ',', '.') }}</td>
            <td style="font-size:9px; color:#6B7280;">{{ $row['unidad'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- ── OBSERVACIONES GENERALES ────────────────────────────────── --}}
@if($presupuesto->observaciones)
<div style="border:1px solid #FCD34D; background:#FFFBEB; padding:7px 10px; margin-top:14px;">
    <div style="font-weight:bold; font-size:10px; color:#92400E; margin-bottom:3px;">Observaciones generales</div>
    <div style="font-size:10px; line-height:1.5;">{{ $presupuesto->observaciones }}</div>
</div>
@endif

{{-- ── PIE DE EMPRESA ──────────────────────────────────────────── --}}
<div style="text-align:center; font-size:8px; color:#374151; padding:5px 0;
            border-top:1px solid #9CA3AF; margin-top:10px; line-height:1.6;">
    Chacabuco 80 (S2500CHB) Cañada de Gómez, Santa Fe, Argentina. &nbsp;&nbsp; Tel: 03471 – 422983 / 15575476<br>
    WhatsApp: 3471575476 &nbsp;&nbsp;&nbsp; Seguinos en Facebook: Pierantonimuebles<br>
    E-mail: administracion@pierantoni.com.ar &nbsp; – &nbsp; WEB: https://pierantoni.com.ar/
</div>

</body>
</html>
