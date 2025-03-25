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
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            text-align: center;
            background-image: url("{{ public_path('storage/' . $plantilla->fondo) }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #000;
        }

        .content {
            position: absolute;
            top: 2%; /* Más arriba */
            left: 10%;
            width: 80%;
            z-index: 2;
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
            width: 350px;
            height: auto;
            margin-bottom: 0;
        }

        h1, h2, h3, p {
            margin: 0.5rem 0;
        }

        h3 strong {
            font-weight: bold;
        }

        h3::before,
        h3::after {
            content: '"';
        }
    </style>
</head>
<body>
    @foreach($participantes as $participante)
        @php
            \Carbon\Carbon::setLocale('es');
            $fechaFormateada = \Carbon\Carbon::parse($plantilla->fecha_emision)->isoFormat('D [de] MMMM [de] YYYY');
        @endphp

 <div class="content">
    <img src="{{ public_path('storage/logo_diploma/CCISUR_LOGO.png') }}" class="logo">

    <h2 style="line-height: 1.5;">OTORGA EL PRESENTE</h2>

    <h1 style="line-height: 1.5;">CERTIFICADO DE PARTICIPACIÓN A:</h1>

    <p class="nombre" style="line-height: 1.6;">{{ $participante->nombre_completo }}</p>

    <p class="info" style="line-height: 1.6;">Por su participación en:</p>

    <h3 style="line-height: 1.6;"><strong>"{{ $capacitacion->nombre }}"</strong></h3>

    <p class="info" style="line-height: 1.5;">{{ $capacitacion->lugar }}, {{ $fechaFormateada }}</p>

    <p class="info" style="line-height: 1.5;"><strong>Impartido por: {{ $capacitacion->impartido_por }}</strong></p>
</div>


        <img src="{{ public_path('storage/' . $plantilla->firma) }}" class="firma">
        <div style="page-break-after: always;"></div>
    @endforeach
</body>
</html>
