<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="robots" content="noindex,nofollow">

    <title>Documento compartido - PRZ</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f3f5f4;
            font-family: Arial, sans-serif;
            color: #24332b;
        }

        header {
            background: #176B45;
            color: white;
            padding: 20px 30px;
        }

        header h2 {
            margin: 0;
            font-size: 21px;
        }

        header p {
            margin: 6px 0 0;
            opacity: .85;
            font-size: 13px;
        }

        main {
            max-width: 1150px;
            margin: 30px auto;
            padding: 0 16px;
        }

        .card {
            background: white;
            padding: 22px;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,.07);
        }

        .archivo {
            font-size: 19px;
            font-weight: 700;
            overflow-wrap: anywhere;
            margin-bottom: 6px;
        }

        .subtitulo {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 20px;
        }

        iframe {
            width: 100%;
            height: 75vh;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .imagen {
            max-width: 100%;
            max-height: 75vh;
            display: block;
            margin: auto;
            object-fit: contain;
        }

        .aviso {
            padding: 25px;
            background: #f3f5f4;
            border-radius: 10px;
            text-align: center;
            color: #555;
        }

        footer {
            text-align: center;
            color: #888;
            font-size: 12px;
            padding: 25px;
        }
    </style>
</head>

<body>

<header>
    <h2>Parque Museo Pedro del Río Zañartu</h2>
    <p>Documento compartido mediante Drive PRZ</p>
</header>

<main>

    <div class="card">

        <div class="archivo">
            {{ $documento->nombre_original }}
        </div>

        <div class="subtitulo">
            Acceso externo de solo lectura
        </div>

        @php
            $urlContenido = route(
                'drive-prz.publico.contenido',
                ['token' => $token]
            );

            $extension = strtolower(
                pathinfo(
                    $documento->nombre_original,
                    PATHINFO_EXTENSION
                )
            );
        @endphp

        @if ($visualizable)

            @if (in_array($extension, [
                'jpg', 'jpeg', 'png', 'gif', 'webp'
            ]))

                <img
                    src="{{ $urlContenido }}"
                    alt="Documento compartido"
                    class="imagen"
                >

            @else

                <iframe
                    src="{{ $urlContenido }}"
                    title="Vista previa del documento"
                    referrerpolicy="no-referrer">
                </iframe>

            @endif

        @else

            <div class="aviso">
                Este formato no admite vista previa
                en la versión de prueba.
            </div>

        @endif

    </div>

</main>

<footer>
    Drive PRZ · Acceso externo a documento compartido
</footer>

</body>
</html>