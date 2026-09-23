<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Catálogo de sillas</title>
    <style>
        * { box-sizing: border-box; }
        @page { margin: 8mm; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1F2937;
            margin: 0;
        }
        h1 {
            margin: 0 0 4px;
            color: #1E3A8A;
            font-size: 18px;
        }
        .subtitle {
            color: #4B5563;
            margin-bottom: 12px;
        }
        .meta {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .meta td {
            padding: 3px 0;
            font-size: 10px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #9CA3AF;
        }
        .report-table th {
            background: #3D68DB;
            color: #FFFFFF;
            border: 1px solid #3D68DB;
            padding: 6px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }
        .report-table td {
            border: 1px solid #D1D5DB;
            padding: 6px;
            vertical-align: top;
        }
        .main-row td {
            background: #EFF6FF;
            font-weight: bold;
        }
        .sub-row td {
            background: #FFFFFF;
            font-size: 9px;
        }
        .item-img {
            width: 58px;
            height: 58px;
            object-fit: contain;
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
        }
        .center { text-align: center; }
        .muted { color: #6B7280; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            background: #FEF3C7;
            color: #92400E;
            font-size: 8px;
            font-weight: bold;
        }
        .page-break-avoid { page-break-inside: avoid; }
    </style>
</head>
<body>
    <h1>Catálogo de sillas</h1>
    <div class="subtitle">
        Insumos de categoría silla con marcas relacionadas y nombres de fantasía.
    </div>

    <table class="meta">
        <tr>
            <td><strong>Fecha:</strong> {{ $fecha->format('d/m/Y H:i') }}</td>
            <td><strong>Total sillas:</strong> {{ $sillas->count() }}</td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 70px;">Imagen</th>
                <th style="width: 90px;">Código</th>
                <th style="width: 180px;">Nombre</th>
                <th style="width: 130px;">Tipo de silla</th>
                <th style="width: 160px;">Proveedor</th>
                <th>Marcas / Nombres de fantasía</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sillas as $data)
                @php
                    /** @var \App\Models\Insumo $insumo */
                    $insumo = $data['insumo'];
                    $marcas = $insumo->marcasSilla;
                @endphp

                <tr class="main-row page-break-avoid">
                    <td class="center">
                        @if ($data['imagen_base64'])
                            <img src="{{ $data['imagen_base64'] }}" class="item-img" alt="{{ $insumo->nombre }}">
                        @else
                            <span class="muted">Sin imagen</span>
                        @endif
                    </td>
                    <td>
                        {{ $insumo->codigo }}
                        @if (! $insumo->activo)
                            <br><span class="badge">Inactivo</span>
                        @endif
                    </td>
                    <td>{{ $insumo->nombre }}</td>
                    <td>{{ $insumo->tipoSilla?->nombre ?? '—' }}</td>
                    <td>{{ $insumo->proveedor?->razon_social ?? '—' }}</td>
                    <td>
                        @if ($marcas->isEmpty())
                            <span class="muted">Sin marcas relacionadas</span>
                        @else
                            {{ $marcas->count() }} {{ $marcas->count() === 1 ? 'marca' : 'marcas' }}
                        @endif
                    </td>
                </tr>

                @foreach ($marcas as $marcaSilla)
                    <tr class="sub-row">
                        <td></td>
                        <td colspan="4">
                            <strong>Marca:</strong> {{ $marcaSilla->marca?->nombre ?? '—' }}
                        </td>
                        <td>
                            <strong>Nombre de fantasía:</strong> {{ filled($marcaSilla->nombre_fantasia) ? $marcaSilla->nombre_fantasia : '—' }}
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" class="center muted">No hay insumos de categoría silla.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
