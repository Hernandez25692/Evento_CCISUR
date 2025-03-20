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
            text-align: center;
            position: relative;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            background-image: url("{{ public_path('storage/' . $plantilla->fondo) }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .content {
            position: absolute;
            top: 22%; /* Ajustar más arriba */
            width: 80%;
            left: 10%;
            text-align: center;
            z-index: 2;
            font-size: 20px;
            background-color: rgba(255, 255, 255, 0.3); /* Fondo semitransparente */
            padding: 10px;
            border-radius: 10px;
        }
        .nombre {
            font-size: 30px;
            font-weight: bold;
            margin-top: 15px;
        }
        .info {
            font-size: 18px;
            margin-top: 10px;
        }
        .firma {
            position: absolute;
            bottom: 80px; /* Ajustar más arriba si es necesario */
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
        }
    </style>
</head>
<body>
    @foreach($participantes as $participante)
        <div class="content">
            <h2>OTORGA EL PRESENTE</h2>
            <h1>CERTIFICADO DE PARTICIPACIÓN</h1>
            <p class="info">A:</p>
            <p class="nombre">{{ $participante->nombre_completo }}</p>
            <p class="info">Por su participación en el webinar:</p>
            <h3><strong>{{ strtoupper($capacitacion->nombre) }}</strong></h3>
            <p class="info">
                {{ $capacitacion->lugar }}, {{ date('d \d\e F \d\e Y', strtotime($plantilla->fecha_emision)) }}
            </p>
            <p class="info"><strong>Impartido por: {{ $capacitacion->impartido_por }}</strong></p>
        </div>
        <img src="{{ public_path('storage/' . $plantilla->firma) }}" class="firma">
        <div style="page-break-after: always;"></div>
    @endforeach
</body>
</html>
