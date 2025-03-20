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
            top: 40%; /* Mantiene el texto más arriba */
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            text-align: center;
            z-index: 2;
            font-size: 20px;
            background-color: rgba(255, 255, 255, 0.3);
            padding: 15px;
            border-radius: 10px;
        }
        .nombre {
            font-size: 30px;
            font-weight: bold;
            margin-top: 10px;
        }
        .info {
            font-size: 18px;
            margin-top: 5px;
        }
        .firma {
            position: absolute;
            bottom: 12%;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
        }
        /* Estilo del Logo */
        .logo {
            width: 150px; /* Tamaño adecuado */
            display: block;
            margin: 0 auto 5px auto; /* Reduce el margen inferior para pegarlo más al texto */
        }
    </style>
</head>
<body>
    @foreach($participantes as $participante)
        <div class="content">
            <!-- Logo centrado y más pegado al texto -->
            <img src="{{ public_path('storage/logo_diploma/Logo_Diploma.png') }}" class="logo" alt="Logo Diploma">

            <h2 style="margin-top: 0;">OTORGA EL PRESENTE</h2> <!-- Se elimina margen superior para pegarlo más al logo -->
            <h1>CERTIFICADO DE PARTICIPACIÓN</h1>
            <p class="info">A:</p>
            <p class="nombre">{{ $participante->nombre_completo }}</p>
            <p class="info">Por su participación en:</p>
            <h3><strong>{{ strtoupper($capacitacion->nombre) }}</strong></h3>
            <p class="info">
                {{ $capacitacion->lugar }}, {{ \Carbon\Carbon::parse($plantilla->fecha_emision)->translatedFormat('d \d\e F \d\e Y') }}
            </p>
            <p class="info"><strong>Impartido por: {{ $capacitacion->impartido_por }}</strong></p>
            
            <!-- Espacio extra para evitar que el texto se sobreponga con la firma -->
            <br><br><br><br>
        </div>
        <!-- Firma centrada con más espacio arriba -->
        <img src="{{ public_path('storage/' . $plantilla->firma) }}" class="firma">
        <div style="page-break-after: always;"></div>
    @endforeach
</body>
</html>
