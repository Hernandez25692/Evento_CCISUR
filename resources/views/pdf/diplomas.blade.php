<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Diploma de Participación</title>
    @php
        $fonts = [
            'DemiBold' => public_path('fonts/visby/VisbyCF-DemiBold.otf'),
            'Heavy' => public_path('fonts/visby/VisbyCF-Heavy.otf'),
            'Light' => public_path('fonts/visby/VisbyCF-Light.otf'),
        ];
    @endphp
    <style>
        @page {
            size: letter {{ $plantilla->orientacion == 'vertical' ? 'portrait' : 'landscape' }};
            margin: 0;
        }

        body {
            font-family: 'Visby-Light';
            margin: 0;
            padding: 0;
            background-image: url('{{ public_path("storage/{$plantilla->fondo}") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #000;
        }

        .diploma-container {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            text-align: center;
            height: 100vh;
            padding: 2rem 6rem 6rem;
            box-sizing: border-box;
            position: relative;
        }

        .logo {
            width: 380px;
            height: auto;
            margin-bottom: 0;
        }

        .titulo-secundario {
            font-family: 'Visby-DemiBold';
            font-size: 20px;
            margin-bottom: 1.8rem;
            max-width: 85%;
            line-height: 1.5;
            word-wrap: break-word;
            white-space: normal;
            display: inline-block;
            text-align: center;
        }

        .titulo-principal {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .nombre {
            font-family: 'Visby-Heavy';
            font-size: 30px;
            font-weight: bold;
            margin: 0.8rem 0;
        }

        .nombre.generico {
            color: #004aad;
            text-decoration: underline;
        }

        .info {
            font-family: 'Visby-Light';
            font-size: 20px;
            margin: 0.5rem 0;
        }

        .actividad {
            font-family: 'Visby-Heavy';
            font-size: 20px;
            font-weight: bold;
            margin: 1rem 0;
        }

        .firmas-nombres {
            position: absolute;
            bottom: 100px;
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
        }

        .firma-linea {
            border-top: 1.2px solid #000;
            width: 155px;
            margin: 4px auto 2px;
        }

        .firma-nombre {
            font-family: 'Visby-DemiBold';
            font-size: 16px;
            margin-top: 0;
            font-weight: bold;
        }

        .qr {
            position: absolute;
            bottom: 100px;
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

            $qrTexto = route('certificados.validarQR') . '?identidad=' . $participante->identidad;
            $qrSvg = QrCode::format('svg')->size(100)->generate($qrTexto);
            $qrBase64 = base64_encode($qrSvg);
        @endphp

        <div class="diploma-container">
            <br><br><br><br><br><br><br><br>

            <p class="titulo-secundario">
                @if ($plantilla->tipo_certificado === 'convenio')
                    {{ $plantilla->titulo_convenio ?? '---' }}
                @else
                    La Cámara de Comercio e Industrias del Sur otorga el presente <br>certificado de participación a:
                @endif
            </p>

            <br>

            <div style="display: inline-block; border-bottom: 3px solid #000; padding: 0 30px; margin-bottom: 0.8rem;">
                <p class="nombre {{ $plantilla->tipo_certificado === 'generico' ? 'generico' : '' }}"
                    style="color: #004aad; text-decoration: none; margin: 0;">
                    {{ $participante->nombre_completo }}
                </p>
            </div>

            <p class="info">Por su participación en {{ $capacitacion->tipo_formacion ?? 'virtual' }}:</p>
            <p class="actividad">"{{ $capacitacion->nombre }}"</p>
            <p class="info">en modalidad {{ $capacitacion->modalidad ?? 'virtual' }} con duración de
                {{ $capacitacion->duracion ?? 'N horas' }} horas.</p>
            <p class="info">{{ $capacitacion->lugar }}, {{ $fechaFormateada }}.</p>

            @if ($plantilla->tipo_certificado === 'generico')
                <p class="info"><strong>Impartido por: {{ $capacitacion->impartido_por }}</strong></p>
            @endif

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
           {{--
           <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" class="qr" alt="QR">
           --}}

        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
