<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel {{ $presupuesto->codigo }}</title>
    <style>
        * { box-sizing: border-box; }
        html, body { margin: 0; height: 100%; font-family: system-ui, sans-serif; background: #e5e7eb; }
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 20px;
            background: #1E3A8A;
            color: #fff;
        }
        .toolbar h1 { font-size: 16px; margin: 0; font-weight: 600; }
        .toolbar a {
            background: #fff;
            color: #1E3A8A;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        .toolbar a:hover { background: #dbeafe; }
        .sheet {
            height: calc(100% - 52px);
            overflow: auto;
            padding: 20px;
        }
        .sheet table {
            border-collapse: collapse;
            background: #fff;
            min-width: 100%;
        }
        .sheet th, .sheet td {
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            font-size: 13px;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <h1>Presupuesto {{ $presupuesto->codigo }}</h1>
        <a href="{{ $downloadUrl }}">Descargar Excel</a>
    </div>
    <div class="sheet">
        @include('exports.presupuesto_excel')
    </div>
</body>
</html>
