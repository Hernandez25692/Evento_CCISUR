<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Diploma de Participación</title>
    @php
        // Dimensiones reales de la plantilla (si se conocen) para que el PDF
        // respete su proporción en vez de forzar siempre tamaño "letter".
        $anchoIn = $plantilla->fondo_width ? $plantilla->fondo_width / 96 : null;
        $altoIn = $plantilla->fondo_height ? $plantilla->fondo_height / 96 : null;

        // Posiciones configurables de cada campo (o valores por defecto si
        // la plantilla nunca se configuró en el editor de posiciones).
        $campos = \App\Services\DiplomaCamposService::resolve($plantilla->campos ?? null);
    @endphp
    <style>
        @page {
            @if ($anchoIn && $altoIn)
                size: {{ $anchoIn }}in {{ $altoIn }}in;
            @else
                size: letter {{ $plantilla->orientacion == 'vertical' ? 'portrait' : 'landscape' }};
            @endif
            margin: 0;
        }

        body {
            font-family: 'Visby-Light';
            margin: 0;
            padding: 0;
            background-image: url('{{ public_path("storage/{$plantilla->fondo}") }}');
            background-size: {{ $anchoIn && $altoIn ? '100% 100%' : 'cover' }};
            background-position: center;
            background-repeat: no-repeat;
            color: #000;
        }

        .diploma-container {
            position: relative;
            width: 100%;
            height: 100vh;
            box-sizing: border-box;
        }

        /* Cada campo se posiciona con left/top (en % del lienzo) definidos
           inline por campo, ancla en su punto central para que el texto
           crezca simétricamente si se ajusta a varias líneas. */
        .campo {
            position: absolute;
            transform: translate(-50%, -50%);
            text-align: center;
            max-width: 80%;
            white-space: normal;
            line-height: 1.4;
        }

        .titulo-secundario {
            font-family: 'Visby-DemiBold';
            line-height: 1.5;
            word-wrap: break-word;
        }

        .nombre {
            font-family: 'Visby-Heavy';
            font-weight: bold;
        }

        .info {
            font-family: 'Visby-Light';
        }

        .actividad {
            font-family: 'Visby-Heavy';
            font-weight: bold;
        }

        .firma-box {
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
            margin-top: 0;
            font-weight: bold;
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

            <div class="campo"
                style="left:{{ $campos['titulo_secundario']['x'] }}%; top:{{ $campos['titulo_secundario']['y'] }}%; font-size:{{ $campos['titulo_secundario']['font_size'] }}px;">
                <p class="titulo-secundario">
                    @if ($plantilla->tipo_certificado === 'convenio')
                        {{ $plantilla->titulo_convenio ?? '---' }}
                    @else
                        La Cámara de Comercio e Industrias del Sur otorga el presente <br>certificado de
                        participación a:
                    @endif
                </p>
            </div>

            <div class="campo" style="left:{{ $campos['nombre']['x'] }}%; top:{{ $campos['nombre']['y'] }}%;">
                <div
                    style="display: inline-block; border-bottom: 3px solid #000; padding: 0 30px; margin-bottom: 0.8rem;">
                    <p class="nombre {{ $plantilla->tipo_certificado === 'generico' ? 'generico' : '' }}"
                        style="color: #004aad; text-decoration: none; margin: 0; font-size:{{ $campos['nombre']['font_size'] }}px;">
                        {{ $participante->nombre_completo }}
                    </p>
                </div>
            </div>

            <div class="campo"
                style="left:{{ $campos['participacion']['x'] }}%; top:{{ $campos['participacion']['y'] }}%; font-size:{{ $campos['participacion']['font_size'] }}px;">
                <p class="info">Por su participación en {{ $capacitacion->tipo_formacion ?? 'virtual' }}:</p>
            </div>

            <div class="campo"
                style="left:{{ $campos['actividad']['x'] }}%; top:{{ $campos['actividad']['y'] }}%; font-size:{{ $campos['actividad']['font_size'] }}px;">
                <p class="actividad">"{{ $capacitacion->nombre }}"</p>
            </div>

            <div class="campo"
                style="left:{{ $campos['modalidad_duracion']['x'] }}%; top:{{ $campos['modalidad_duracion']['y'] }}%; font-size:{{ $campos['modalidad_duracion']['font_size'] }}px;">
                <p class="info">en modalidad {{ $capacitacion->modalidad ?? 'virtual' }} con duración de
                    {{ $capacitacion->duracion ?? 'N horas' }} horas.</p>
            </div>

            <div class="campo"
                style="left:{{ $campos['lugar_fecha']['x'] }}%; top:{{ $campos['lugar_fecha']['y'] }}%; font-size:{{ $campos['lugar_fecha']['font_size'] }}px;">
                <p class="info">{{ $capacitacion->lugar }}, {{ $fechaFormateada }}.</p>
            </div>

            @if ($plantilla->tipo_certificado === 'generico')
                <div class="campo"
                    style="left:{{ $campos['impartido_por']['x'] }}%; top:{{ $campos['impartido_por']['y'] }}%; font-size:{{ $campos['impartido_por']['font_size'] }}px;">
                    <p class="info"><strong>Impartido por: {{ $capacitacion->impartido_por }}</strong></p>
                </div>
            @endif

            {{-- Firmas --}}
            @if ($mostrarFirma1)
                <div class="campo firma-box"
                    style="left:{{ $campos['firma_1']['x'] }}%; top:{{ $campos['firma_1']['y'] }}%;">
                    <img src="{{ storage_path('app/public/' . $plantilla->firma_1) }}" class="firma-img"
                        alt="Firma 1">
                    <div class="firma-linea"></div>
                    <p class="firma-nombre" style="font-size:{{ $campos['firma_1']['font_size'] }}px;">
                        {{ $plantilla->nombre_firma_1 }}</p>
                </div>
            @endif

            @if ($mostrarFirma2)
                <div class="campo firma-box"
                    style="left:{{ $campos['firma_2']['x'] }}%; top:{{ $campos['firma_2']['y'] }}%;">
                    <img src="{{ storage_path('app/public/' . $plantilla->firma_2) }}" class="firma-img"
                        alt="Firma 2">
                    <div class="firma-linea"></div>
                    <p class="firma-nombre" style="font-size:{{ $campos['firma_2']['font_size'] }}px;">
                        {{ $plantilla->nombre_firma_2 }}</p>
                </div>
            @endif

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
