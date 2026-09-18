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
    @php($autoguardar = $autoguardar ?? false)
    <div id="pdf-container" style="width:100%;height:100%;">
        @unless($autoguardar)
            <object data="{{ $pdfUrl }}" type="application/pdf" width="100%" height="100%">
                <div class="fallback">
                    <p>Tu navegador no pudo mostrar el PDF directamente.</p>
                    <a href="{{ $pdfUrl }}" download="{{ $filename }}">Descargar PDF</a>
                </div>
            </object>
        @else
            <div class="fallback" id="pdf-loading">
                <p>Generando PDF...</p>
            </div>
        @endunless
    </div>
    @if($autoguardar)
        <script>
            (async function () {
                const pdfUrl = @json($pdfUrl);
                const filename = @json($filename);
                const container = document.getElementById('pdf-container');

                const renderObject = (src) => {
                    const objectEl = document.createElement('object');
                    objectEl.setAttribute('data', src);
                    objectEl.setAttribute('type', 'application/pdf');
                    objectEl.setAttribute('width', '100%');
                    objectEl.setAttribute('height', '100%');

                    const fallback = document.createElement('div');
                    fallback.className = 'fallback';
                    const message = document.createElement('p');
                    message.textContent = 'Tu navegador no pudo mostrar el PDF directamente.';
                    const downloadLink = document.createElement('a');
                    downloadLink.href = src;
                    downloadLink.download = filename;
                    downloadLink.textContent = 'Descargar PDF';
                    fallback.append(message, downloadLink);
                    objectEl.appendChild(fallback);

                    container.replaceChildren(objectEl);
                };

                try {
                    const response = await fetch(pdfUrl, { credentials: 'same-origin' });
                    if (!response.ok) {
                        throw new Error('No se pudo generar el PDF');
                    }

                    const blob = await response.blob();
                    const blobUrl = URL.createObjectURL(blob);
                    renderObject(blobUrl);

                    const link = document.createElement('a');
                    link.href = blobUrl;
                    link.download = filename;
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                } catch (e) {
                    renderObject(pdfUrl);
                }
            })();
        </script>
    @endif
</body>
</html>
