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
            justify-content: flex-start;
            /* 👈 Empieza arriba */
            align-items: center;
            text-align: center;
            height: 100vh;
            padding: 2rem 6rem 6rem;
            /* 👈 También podés reducir el padding superior */
            box-sizing: border-box;
            position: relative;
        }


        .logo {
            width: 380px;
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

        .firmas-nombres {
            position: absolute;
            bottom: 100px;
            /* 👈 Antes 50px. Aumentalo para subirlas */
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            gap: 100px;
        }


        .firma-box {
            display: inline-block;
            text-align: center;
            width: 220px;
        }

        .firma-img {
            height: 155px;
            object-fit: contain;
            display: block;
            margin: 0 auto -70px;
            /* Ajusta para que quede justo encima de la línea */
        }

        .firma-linea {
            border-top: 1.2px solid #000;
            /* un poco más delgada */

            width: 155px;
            /* más corta */

            margin: 4px auto 2px;
        }

        .firma-nombre {
            font-size: 14px;
            margin-top: 0;
            font-weight: bold;
            /* Negrita para el nombre */
        }

        .qr {
            position: absolute;
            bottom: 100px;
            /* 👈 lo sube bastante */
            right: 190px;
            width: 60px;
            opacity: 0.9;
        }



        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    @foreach ($participantes as $index => $participante)
        @php
            \Carbon\Carbon::setLocale('es');
            $fechaFormateada = \Carbon\Carbon::parse($plantilla->fecha_emision)->isoFormat('D [de] MMMM [de] YYYY');
            $mostrarFirma1 = $plantilla->firma_1 && $plantilla->nombre_firma_1;
            $mostrarFirma2 = $plantilla->firma_2 && $plantilla->nombre_firma_2;

            // QR en formato imagen PNG base64
            $qrTexto = route('certificados.validarQR') . '?identidad=' . $participante->identidad;
            $qrSvg = QrCode::format('svg')->size(100)->generate($qrTexto);
            $qrBase64 = base64_encode($qrSvg);
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
            <div class="firmas-nombres">
                @if ($mostrarFirma1)
                    <div class="firma-box">
                        <img src="{{ storage_path('app/public/' . $plantilla->firma_1) }}" class="firma-img"
                            alt="Firma 1">
                        <div class="firma-linea"></div>
                        <p class="firma-nombre">{{ $plantilla->nombre_firma_1 }}</p>
                    </div>
                @endif

                @if ($mostrarFirma2)
                    <div class="firma-box">
                        <img src="{{ storage_path('app/public/' . $plantilla->firma_2) }}" class="firma-img"
                            alt="Firma 2">
                        <div class="firma-linea"></div>
                        <p class="firma-nombre">{{ $plantilla->nombre_firma_2 }}</p>
                    </div>
                @endif
            </div>

            {{-- QR --}}
            <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" class="qr" alt="QR">
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>

</html>
