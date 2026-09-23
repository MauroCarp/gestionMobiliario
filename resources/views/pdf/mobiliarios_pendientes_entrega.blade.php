<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Mobiliarios pendientes de entrega</title>
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
        .badge-finished {
            background: #DCFCE7;
            color: #166534;
        }
        .page-break-avoid { page-break-inside: avoid; }
    </style>
</head>
<body>
    <h1>Mobiliarios pendientes de entrega</h1>
    <div class="subtitle">
        Reporte de mobiliarios filtrados con detalle por presupuesto, agencia y estado actual.
    </div>

    <table class="meta">
        <tr>
            <td><strong>Fecha:</strong> {{ $fecha->format('d/m/Y H:i') }}</td>
            <td><strong>Marca:</strong>
                @if ($marca)
                    {{ $marca->nombre }}
                @elseif ($marcaTab === 'sin_marca')
                    Sin marca
                @else
                    Todas
                @endif
            </td>
            <td><strong>Total mobiliarios:</strong> {{ $mobiliarios->count() }}</td>
        </tr>
        <tr>
            <td colspan="3"><strong>Etapa:</strong>
                @if (($etapa ?? 'todas') === 'todas')
                    Todas
                @else
                    {{ $etapa }}
                @endif
            </td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 70px;">Imagen</th>
                <th style="width: 90px;">Marca</th>
                <th style="width: 85px;">Código</th>
                <th style="width: 170px;">Nombre</th>
                <th style="width: 55px;" class="center">Cantidad</th>
                <th>Atributos / Detalle</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mobiliarios as $data)
                @php
                    /** @var \App\Models\Mobiliario $mobiliario */
                    $mobiliario = $data['mobiliario'];
                @endphp

                <tr class="main-row page-break-avoid">
                    <td class="center">
                        @if ($data['imagen_base64'])
                            <img src="{{ $data['imagen_base64'] }}" class="item-img" alt="{{ $mobiliario->nombre }}">
                        @else
                            <span class="muted">Sin imagen</span>
                        @endif
                    </td>
                    <td>{{ $mobiliario->marcas->pluck('nombre')->join(', ') ?: '—' }}</td>
                    <td>{{ $mobiliario->codigo_interno }}</td>
                    <td>{{ $mobiliario->nombre }}</td>
                    <td class="center">{{ $data['cantidad'] }}</td>
                    <td>{{ $data['atributos'] }}</td>
                </tr>

                @foreach ($data['items'] as $item)
                    @php
                        /** @var \App\Models\PresupuestoItem $item */
                        $presupuesto = $item->presupuesto;
                        $descripcion = $item->descripcion_override
                            ?: $item->mobiliario?->descripcion
                            ?: '—';
                    @endphp
                    <tr class="sub-row">
                        <td></td>
                        <td colspan="2">
                            <strong>Presupuesto:</strong> {{ $presupuesto?->codigo ?? '—' }}<br>
                            <strong>Agencia:</strong> {{ $presupuesto?->agencia?->nombre ?? '—' }}
                        </td>
                        <td>
                            <strong>Descripción:</strong> {{ $descripcion }}<br>
                            @if (filled($item->observaciones))
                                <span class="muted">Obs.: {{ $item->observaciones }}</span>
                            @endif
                        </td>
                        <td class="center">{{ $item->cantidadPendiente() }}</td>
                        <td>
                            <span class="badge">{{ $item->estaEntregaParcial() ? 'Parcial' : 'Pendiente' }}</span>
                            @if ($item->estaFinalizado())
                                <span class="badge badge-finished">Finalizado</span>
                            @endif
                            <br>
                            <strong>Etapa actual:</strong> {{ $item->etapa_actual_produccion }}<br>
                            <strong>Progreso:</strong> {{ $item->progreso_produccion }}
                            @if ($item->sector)
                                <br><strong>Sector:</strong> {{ $item->sector->nombre }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" class="center muted">No hay mobiliarios pendientes para el filtro seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
