<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producción {{ $codigo }}</title>
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
            background: #7C3AED; color: #fff; padding: 10px 22px;
            border-radius: 6px; text-decoration: none; font-size: 14px;
        }
        .fallback a:hover { background: #6D28D9; }
    </style>
</head>
<body>
    <object data="{{ $pdfUrl }}" type="application/pdf" width="100%" height="100%">
        <div class="fallback">
            <p>Tu navegador no pudo mostrar el PDF directamente.</p>
            <a href="{{ $pdfUrl }}" download="{{ $filename }}">Descargar PDF</a>
        </div>
    </object>

    @if(!empty($planoUrl))
    <div style="
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    ">
        <a href="{{ $planoUrl }}" target="_blank" style="
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #7C3AED;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        ">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20H6a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v3M13 4v5h5M10 17h8m0 0l-3-3m3 3l-3 3"/>
            </svg>
            Ver Plano / Layout
        </a>
    </div>
    @else
    {{-- DEBUG: plano no encontrado --}}
    <div style="
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        background: #1e1e1e;
        color: #f8f8f2;
        font-family: monospace;
        font-size: 12px;
        padding: 14px 18px;
        border-radius: 8px;
        max-width: 500px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.6);
        overflow: auto;
        max-height: 80vh;
    ">
        <strong style="color:#ff79c6;">⚠ DEBUG: Plano no encontrado</strong>
        <pre style="margin-top:8px; white-space:pre-wrap;">{{ json_encode($planoDebug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
    @endif
</body>
</html>
