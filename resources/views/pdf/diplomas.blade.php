<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Diploma de Participación</title>
    <style>
        @page {
            size: letter {{ $plantilla->orientacion == 'vertical' ? 'portrait' : 'landscape' }};
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            background-image: url("{{ public_path('storage/' . $plantilla->fondo) }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .content {
            position: absolute;
            top: 9%;
            left: 10%;
            width: 80%;
            z-index: 2;
            color: #000;
        }

        .nombre {
            font-size: 30px;
            font-weight: bold;
            margin-top: 0;
        }

        .info {
            font-size: 18px;
            margin-top: 5px;
        }

        .firma {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
        }

        .logo {
            width: 120px;
            margin-bottom: 0; /* Elimina espacio bajo el logo */
        }

        h2, h1 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    @foreach($participantes as $participante)
        <div class="content">
            <img src="{{ public_path('storage/logo_diploma/images.jpeg') }}" class="logo">
            <h2>OTORGA EL PRESENTE</h2>
            <h1>CERTIFICADO DE PARTICIPACIÓN</h1>
            <p class="info">A:</p>
            <p class="nombre">{{ $participante->nombre_completo }}</p>
            <p class="info">Por su participación en :</p>
            <h3><strong>{{ strtoupper($capacitacion->nombre) }}</strong></h3>
            <p class="info">
                {{ $capacitacion->lugar }}, {{ \Carbon\Carbon::parse($plantilla->fecha_emision)->translatedFormat('d \d\e F \d\e Y') }}
            </p>
            <p class="info"><strong>Impartido por: {{ $capacitacion->impartido_por }}</strong></p>
        </div>

        <img src="{{ public_path('storage/' . $plantilla->firma) }}" class="firma">
        <div style="page-break-after: always;"></div>
    @endforeach
</body>
</html>
