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
            background-image: url("{{ storage_path('app/public/' . $plantilla->fondo) }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #000;
        }

        .diploma-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100vh;
            padding: 4rem 6rem 6rem;
            box-sizing: border-box;
        }

         .logo {
            width: 350px;
            height: auto;
            margin-bottom: 0;
        }

        .titulo-secundario {
            font-size: 20px;
            font-weight: normal;
            margin-bottom: 0.3rem;
        }

        .titulo-principal {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .nombre {
            font-size: 28px;
            font-weight: bold;
            margin: 0.8rem 0;
        }

        .info {
            font-size: 18px;
            margin: 0.5rem 0;
        }

        .actividad {
            font-size: 22px;
            font-weight: bold;
            margin: 1rem 0;
        }

        .firma-container {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 60px;
        }

        .firma {
            width: 180px;
            height: auto;
        }

        h3::before,
        h3::after {
            content: '"';
        }

        /* Asegura que en cada página solo haya un diploma */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

@foreach($participantes as $index => $participante)
    @php
        \Carbon\Carbon::setLocale('es');
        $fechaFormateada = \Carbon\Carbon::parse($plantilla->fecha_emision)->isoFormat('D [de] MMMM [de] YYYY');

        $soloFirma1 = $plantilla->firma_1 && !$plantilla->firma_2;
        $soloFirma2 = $plantilla->firma_2 && !$plantilla->firma_1;
        $ambasFirmas = $plantilla->firma_1 && $plantilla->firma_2;
    @endphp

    <div class="diploma-container">
        <img src="{{ public_path('storage/logo_diploma/CCISUR_LOGO.png') }}" class="logo" alt="Logo">
        <p class="titulo-secundario">OTORGA EL PRESENTE</p>
        <p class="titulo-principal">CERTIFICADO DE PARTICIPACIÓN A:</p>
        <p class="nombre">{{ $participante->nombre_completo }}</p>
        <p class="info">Por su participación en:</p>
        <p class="actividad">{{ $capacitacion->nombre }}</p>
        <p class="info">{{ $capacitacion->lugar }}, {{ $fechaFormateada }}</p>
        <p class="info"><strong>Impartido por: {{ $capacitacion->impartido_por }}</strong></p>

        {{-- Firmas --}}
        @if ($soloFirma1)
            <div class="firma-container">
                <img src="{{ storage_path('app/public/' . $plantilla->firma_1) }}" class="firma" alt="Firma 1">
            </div>
        @elseif ($soloFirma2)
            <div class="firma-container">
                <img src="{{ storage_path('app/public/' . $plantilla->firma_2) }}" class="firma" alt="Firma 2">
            </div>
        @elseif ($ambasFirmas)
            <div class="firma-container">
                <img src="{{ storage_path('app/public/' . $plantilla->firma_1) }}" class="firma" alt="Firma 1">
                <img src="{{ storage_path('app/public/' . $plantilla->firma_2) }}" class="firma" alt="Firma 2">
            </div>
        @endif
    </div>

    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach

</body>
</html>
