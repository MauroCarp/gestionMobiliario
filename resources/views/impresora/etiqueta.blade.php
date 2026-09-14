<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiqueta</title>
    <style>
        @page {
            size: 145mm 100mm;
            margin: 0;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            color: #333;
            font-family: Arial, Helvetica, sans-serif;
        }

        .etiqueta {
            width: 145mm;
            height: 100mm;
            padding: 7mm 8mm 8mm;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 7mm;
            overflow: hidden;
            page-break-after: always;
            page-break-inside: avoid;
            background: #fff;
        }

        .etiqueta:last-child {
            page-break-after: auto;
        }

        .logo {
            display: block;
            width: auto;
            max-width: 128mm;
            height: auto;
            max-height: 22mm;
            object-fit: contain;
            object-position: left center;
        }

        .campo {
            margin: 0;
            font-size: 9mm;
            line-height: 1.2;
            font-weight: bolder;
        }

        .campo .valor {
            display: block;
            margin-top: 1mm;
        }

        @media screen {
            body {
                background: #e5e5e5;
                padding: 16px;
            }

            .etiqueta {
                margin: 0 auto 16px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
            }
        }

        @media print {
            html, body {
                width: 145mm;
                height: 100mm;
                background: #fff;
            }

            .etiqueta {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    @for ($i = 1; $i <= $cantidad; $i++)
        <article class="etiqueta">
            <img
                class="logo"
                src="{{ asset('images/logo-empresa.png') }}"
                alt="Orlando Pierantoni S.R.L."
            >

            <p class="campo">
                Mobiliario: {{ $marca->nombre }} - {{ $mobiliario->codigo_interno }} - {{ $mobiliario->nombre }}
            </p>

            <p class="campo">
                Embalado y controlado por: {{ $empleados->pluck('legajo')->join(', ') }}
            </p>
        </article>
    @endfor

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
