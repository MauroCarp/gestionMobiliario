<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto {{ $codigo }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { width: 100%; height: 100%; overflow: hidden; background: #525659; }
        object { display: block; width: 100%; height: 100%; }
        .fallback {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; height: 100%; color: #fff;
            font-family: sans-serif; gap: 12px; text-align: center; padding: 20px;
        }
        .fallback p { font-size: 15px; }
        .fallback a {
            background: #1E3A8A; color: #fff; padding: 10px 22px;
            border-radius: 6px; text-decoration: none; font-size: 14px;
        }
        .fallback a:hover { background: #1e4fc2; }
    </style>
</head>
<body>
    <object data="{{ $pdfUrl }}" type="application/pdf" width="100%" height="100%">
        <div class="fallback">
            <p>Tu navegador no pudo mostrar el PDF directamente.</p>
            <a href="{{ $pdfUrl }}" download="{{ $filename }}">Descargar PDF</a>
        </div>
    </object>
</body>
</html>
