<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Presupuesto {{ $presupuesto->codigo }}</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1F2937;
            margin: 0;
            padding: 15px 25px 20px;
        }

        /* ── CABECERA ─────────────────────────────────────────── */
        .header-table { width: 100%; margin-bottom: 10px; }

        .header-marca { width: 150px; vertical-align: middle; }

        .header-agency { vertical-align: middle; padding: 0 14px; }
        .header-agency .agency-name { font-size: 14px; font-weight: bold; color: #1E3A8A; }
        .header-agency .agency-sub  { font-size: 9px; color: #6B7280; margin-top: 2px; }

        .header-empresa { width: 200px; text-align: right; vertical-align: middle; }
        .header-empresa .empresa-codigo {
            font-size: 10px; font-weight: bold;
            color: #374151; margin-top: 6px; text-align: right;
        }

        .header-divider { border: none; border-top: 3px solid #1E3A8A; margin: 0 0 10px; }

        /* ── FECHA DE EMISIÓN ─────────────────────────────────── */
        .fecha-row { text-align: right; font-size: 10px; margin-bottom: 12px; }
        .fecha-row .lbl { color: #6B7280; }
        .fecha-row .val { font-weight: bold; }

        /* ── TABLA DE ITEMS ───────────────────────────────────── */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 0; border: 1px solid #9CA3AF; }
        .items-table thead tr { background: #1E3A8A; color: #FFFFFF; }
        .items-table thead th {
            padding: 7px 8px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border: 1px solid #4B6CB7;
        }
        .items-table thead th.center { text-align: center; }
        .items-table thead th.right  { text-align: right; }
        .items-table tbody tr.even { background: #F9FAFB; }
        .items-table tbody td { padding: 7px 8px; vertical-align: top; font-size: 10px; border: 1px solid #D1D5DB; }
        .items-table tbody td.center { text-align: center; }
        .items-table tbody td.right  { text-align: right; }

        /* ── SECTOR HEADER ───────────────────────────────────────── */
        .sector-header {
            background: #1E3A8A;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 6px 10px;
            margin-top: 14px;
            margin-bottom: 0;
        }
        .sector-total-row td {
            background: #EFF6FF;
            font-weight: bold;
            font-size: 10px;
            border-top: 2px solid #1E3A8A;
        }
        .sector-spacer { height: 14px; }

        .item-num    { color: #6B7280; font-size: 10px; font-weight: bold; }
        .item-img-cell { overflow: hidden; }
        .item-img    { width: 106px; height: auto; display: block; }
        .item-no-img {
            width: 106px; height: 90px;
            background: #F3F4F6; border: 1px solid #E5E7EB;
            font-size: 8px; color: #9CA3AF;
            text-align: center; line-height: 90px;
        }
        .item-code   { font-size: 9px; color: #9CA3AF; margin-top: 2px; }
        .item-desc   { font-size: 9px; color: #6B7280; margin-top: 3px; line-height: 1.4; }
        .item-obs    { font-size: 9px; color: #374151; margin-top: 3px; }
        .item-qty    { font-size: 13px; font-weight: bold; }
        .item-price  { font-size: 10px; }
        .notas-line  { border-bottom: 1px solid #D1D5DB; height: 14px; margin-bottom: 3px; }

        /* ── OBSERVACIONES GENERALES ──────────────────────────── */
        .obs-box {
            border: 1px solid #FCD34D;
            background: #FFFBEB;
            padding: 7px 10px;
            margin-bottom: 14px;
        }
        .obs-box .obs-title { font-weight: bold; font-size: 10px; color: #92400E; margin-bottom: 3px; }

        /* ── BASES Y CONDICIONES ──────────────────────────────── */
        .bases-title {
            font-size: 10px;
            font-weight: bold;
            color: #1E3A8A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 12px;
            margin-bottom: 5px;
            padding-bottom: 4px;
            border-bottom: 2px solid #1E3A8A;
        }
        .bases-list {
            margin: 0;
            padding-left: 20px;
            font-size: 8.5px;
            color: #1F2937;
            line-height: 1.6;
        }
        .bases-list li { margin-bottom: 3px; }
        .bases-red { color: #DC2626; font-weight: bold; }

        /* ── PIE EMPRESA ──────────────────────────────────────── */
        .empresa-footer {
            text-align: center;
            font-size: 8px;
            color: #374151;
            padding: 5px 0;
            border-top: 1px solid #9CA3AF;
            margin-top: 5px;
            line-height: 1.6;
        }

        /* ── PIE FIJO (número pág / código) ──────────────────── */
        .footer-fixed {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            border-top: 1px solid #E5E7EB;
            padding: 3px 25px;
            font-size: 7.5px;
            color: #9CA3AF;
        }
        .footer-fixed table { width: 100%; }

        /* page break helpers */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

{{-- ── PIE FIJO (página) ─────────────────────────────────────── --}}
<div class="footer-fixed">
    <table cellpadding="0" cellspacing="0">
        <tr>
            <td>{{ $presupuesto->codigo }}</td>
            <td style="text-align:right;">Generado el {{ now()->format('d/m/Y H:i') }}</td>
        </tr>
    </table>
</div>

{{-- ── CABECERA ────────────────────────────────────────────────── --}}
<table class="header-table" cellpadding="0" cellspacing="0">
    <tr>
        {{-- Izquierda: logo de la marca (Chery) --}}
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

        {{-- Centro: datos de agencia --}}
        <td class="header-agency">
            <div class="agency-name">{{ $agencia->nombre ?? 'Sin agencia' }}</div>
            @if($agencia?->direccion)
                <div class="agency-sub">{{ $agencia->direccion }}</div>
            @endif
            @if($agencia?->responsable)
                <div class="agency-sub">Resp.: {{ $agencia->responsable }}</div>
            @endif
        </td>

        {{-- Derecha: logo empresa + código --}}
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

{{-- ── FECHA DE EMISIÓN ───────────────────────────────────────── --}}
<div class="fecha-row">
    <span class="lbl">Fecha de emisión: </span>
    <span class="val">{{ $presupuesto->fecha_emision->format('d/m/Y') }}</span>
</div>

{{-- ── TABLAS POR SECTOR ───────────────────────────────────── --}}
@php $globalIndex = 1; $grandTotal = 0; $hayPrecios = false; @endphp

@foreach($itemsPorSector as $sectorNombre => $sectorItems)
@php
    $esSinSector = ($sectorNombre === '__sin_sector__');
    $labelSector = $esSinSector ? 'Sin sector asignado' : $sectorNombre;
    $sectorTotal = $sectorItems->sum(fn($i) => (float)($i['item']->subtotal ?? 0));
    $grandTotal += $sectorTotal;
    if ($sectorItems->contains(fn($i) => $i['item']->precio_unitario !== null)) $hayPrecios = true;
@endphp

{{-- Encabezado de sector --}}
<div class="sector-header" style="{{ $esSinSector ? 'background:#6B7280;' : '' }}">
    {{ $labelSector }}
</div>

<table class="items-table" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th style="width:18px;" class="center">#</th>
            <th style="width:122px;" class="center">Imagen</th>
            <th style="width:17%;">Mobiliario</th>
            <th>Descripción / Observaciones</th>
            <th style="width:36px;" class="center">Cant.</th>
            <th style="width:90px;">Notas</th>
            <th style="width:72px;" class="right">Precio</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sectorItems as $idx => $itemData)
        @php
            $item      = $itemData['item'];
            $mob       = $itemData['mobiliario'];
            $insumo    = $itemData['insumo'];
            $imgBase64 = $itemData['imagen_base64'];
            $rowClass  = $idx % 2 === 1 ? 'even' : '';
            $nombreItem = $mob?->nombre ?? $insumo?->nombre ?? '—';
            $codigoItem = $mob?->codigo_interno ?? $insumo?->codigo ?? '';
            $categoriaItem = $mob?->categoria?->nombre ?? ($insumo ? 'Insumo' : '');
        @endphp
        <tr class="{{ $rowClass }}">

            <td class="center">
                <span class="item-num">{{ $globalIndex++ }}</span>
            </td>

            <td class="center item-img-cell">
                @if($imgBase64)
                    <img src="{{ $imgBase64 }}" class="item-img">
                @else
                    <div class="item-no-img">Sin imagen</div>
                @endif
            </td>

            <td>
                <strong>{{ $nombreItem }}</strong>
                <div class="item-code">Cód: {{ $codigoItem }}</div>
                @if($categoriaItem)
                    <div class="item-code">{{ $categoriaItem }}</div>
                @endif
            </td>

            <td>
                @php $desc = $item->descripcion_override ?: $mob?->descripcion; @endphp
                @if($desc)
                    <div class="item-desc">{{ \Illuminate\Support\Str::limit($desc, 230) }}</div>
                @endif
                @if($item->observaciones)
                    <div class="item-obs"><strong>Obs:</strong> {{ $item->observaciones }}</div>
                @endif
            </td>

            <td class="center">
                <span class="item-qty">{{ $item->cantidad }}</span>
            </td>

            <td>
                @if($item->notas_manuales)
                    <div style="font-size:9px;">{{ $item->notas_manuales }}</div>
                @else
                    <div class="notas-line"></div>
                    <div class="notas-line"></div>
                    <div class="notas-line"></div>
                @endif
            </td>

            <td class="right item-price">
                @if($item->precio_unitario !== null)
                    ${{ number_format((float)$item->precio_unitario, 2, ',', '.') }}
                @endif
            </td>

        </tr>
        @endforeach
    </tbody>

    {{-- Subtotal por sector --}}
    @if($sectorTotal > 0)
    <tfoot>
        <tr class="sector-total-row">
            <td colspan="6" style="padding:5px 10px; text-align:right;">
                Subtotal {{ $labelSector }}:
            </td>
            <td style="padding:5px 10px; text-align:right;">
                ${{ number_format($sectorTotal, 2, ',', '.') }}
            </td>
        </tr>
    </tfoot>
    @endif
</table>
<div class="sector-spacer"></div>
@endforeach

{{-- ── TOTAL GENERAL ──────────────────────────────────────────── --}}
@if($hayPrecios && $grandTotal > 0)
<table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
    <tr style="background:#1E3A8A; color:#FFFFFF;">
        <td style="padding:8px 10px; text-align:right; font-weight:bold; font-size:12px;">
            TOTAL GENERAL:
        </td>
        <td style="padding:8px 10px; text-align:right; font-size:13px; font-weight:bold; width:120px;">
            ${{ number_format($grandTotal, 2, ',', '.') }}
        </td>
    </tr>
</table>
@endif

{{-- ── OBSERVACIONES GENERALES ────────────────────────────────── --}}
@if($presupuesto->observaciones)
<div class="obs-box">
    <div class="obs-title">Observaciones generales</div>
    <div style="font-size:10px; line-height:1.5;">{{ $presupuesto->observaciones }}</div>
</div>
@endif

{{-- ── BASES Y CONDICIONES ───────────────────────────────────── --}}
<div class="bases-title">Bases y Condiciones</div>
<ol class="bases-list">
    <li>EL PRESUPUESTO TENDRÁ VALIDEZ DE 5 DÍAS.</li>
    <li>FORMA DE PAGO: 50% MEDIANTE TRANSFERENCIA y 50% ENVIANDO E-CHEQ A 15-30-45 DÍAS AL CONFIRMAR EL PEDIDO.</li>
    <li>PLAZO DE ENTREGA: 50 días aproximadamente al confirmar el presupuesto.</li>
    <li>LA TOTALIDAD DEL MOBILIARIO ESTA REALIZADO BAJO LOS REQUERIMIENTOS DEL CLIENTE, TANTO EN LOS MATERIALES UTILIZADOS, COLORES, CALIDAD Y DISEÑO DE LOS MISMOS.</li>
    <li>EN EL SUPUESTO CASO QUE EL MOBILIARIO NO PUEDA INSTALARSE DEBERÁ SER DESEMBALADO Y CONTROLADO AL MOMENTO DE LA ENTREGA EN CONJUNTO CON LA PARTE VENDEDORA Y COMPRADORA. DE LO CONTRARIO LA EMPRESA NO SE RESPONSABILIZA POR LOS DAÑOS QUE SUFRA EL MISMO.</li>
    <li>LAS IMÁGENES SON ILUSTRATIVAS, PUEDEN VARIAR CON EL PRODUCTO REAL.</li>
    <li class="bases-red">En el supuesto caso de necesitar embalaje para el envío por transporte ajeno a nuestra empresa, se deberá adicionar un 10% sobre el producto.</li>
</ol>

{{-- ── PIE DE EMPRESA ──────────────────────────────────────────── --}}
<div class="empresa-footer">
    Chacabuco 80 (S2500CHB) Cañada de Gómez, Santa Fe, Argentina. &nbsp;&nbsp; Tel: 03471 – 422983 / 15575476<br>
    WhatsApp: 3471575476 &nbsp;&nbsp;&nbsp; Seguinos en Facebook: Pierantonimuebles<br>
    E-mail: administracion@pierantoni.com.ar &nbsp; – &nbsp; WEB: https://pierantoni.com.ar/
</div>

</body>
</html>
