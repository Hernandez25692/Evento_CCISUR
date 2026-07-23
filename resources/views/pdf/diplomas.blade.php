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

        // Posiciones y estilos configurables de cada campo (o valores por
        // defecto si la plantilla nunca se configuró en el editor).
        $campos = \App\Services\DiplomaCamposService::resolve($plantilla->campos ?? null);
        $fuentes = \App\Services\DiplomaCamposService::FUENTES;

        // Textos automáticos (se usan cuando el campo no tiene texto
        // personalizado) — misma fuente que usa el editor, para que ambos
        // coincidan siempre.
        $defecto = \App\Services\DiplomaCamposService::contenidoPorDefecto($capacitacion, $plantilla);

        // Arma el bloque de estilo inline (posición + tipografía) de un campo.
        $estiloCampo = function (string $clave) use ($campos, $fuentes) {
            $c = $campos[$clave];
            $fuente = $fuentes[$c['font_family']]['pdf'] ?? $fuentes['visby-light']['pdf'];

            return sprintf(
                'left:%s%%; top:%s%%; font-size:%dpx; font-family:%s; font-weight:%s; text-decoration:%s; color:%s; text-align:%s;',
                $c['x'],
                $c['y'],
                $c['font_size'],
                $fuente,
                $c['bold'] ? 'bold' : 'normal',
                $c['underline'] ? 'underline' : 'none',
                $c['color'],
                $c['align']
            );
        };
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

        /* Cada campo se posiciona con left/top (en % del lienzo) y su
           tipografía definidos inline por campo (ver $estiloCampo arriba),
           anclado en su punto central para que el texto crezca
           simétricamente si se ajusta a varias líneas. */
        .campo {
            position: absolute;
            transform: translate(-50%, -50%);
            max-width: 80%;
            white-space: normal;
            line-height: 1.4;
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
            margin-top: 0;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @foreach ($participantes as $index => $participante)
        @php
            $mostrarFirma1 = $plantilla->firma_1 && $plantilla->nombre_firma_1 && $campos['firma_1']['visible'];
            $mostrarFirma2 = $plantilla->firma_2 && $plantilla->nombre_firma_2 && $campos['firma_2']['visible'];

            $qrTexto = route('certificados.validarQR') . '?identidad=' . $participante->identidad;
            $qrSvg = QrCode::format('svg')->size(100)->generate($qrTexto);
            $qrBase64 = base64_encode($qrSvg);
        @endphp

        <div class="diploma-container">

            @if ($campos['titulo_secundario']['visible'])
                <div class="campo" style="{{ $estiloCampo('titulo_secundario') }}">
                    {{ $campos['titulo_secundario']['texto'] ?: $defecto['titulo_secundario'] }}
                </div>
            @endif

            @if ($campos['nombre']['visible'])
                <div class="campo" style="{{ $estiloCampo('nombre') }}">
                    {{ $participante->nombre_completo }}
                </div>
            @endif

            @if ($campos['participacion']['visible'])
                <div class="campo" style="{{ $estiloCampo('participacion') }}">
                    {{ $campos['participacion']['texto'] ?: $defecto['participacion'] }}
                </div>
            @endif

            @if ($campos['actividad']['visible'])
                <div class="campo" style="{{ $estiloCampo('actividad') }}">
                    {{ $campos['actividad']['texto'] ?: $defecto['actividad'] }}
                </div>
            @endif

            @if ($campos['modalidad_duracion']['visible'])
                <div class="campo" style="{{ $estiloCampo('modalidad_duracion') }}">
                    {{ $campos['modalidad_duracion']['texto'] ?: $defecto['modalidad_duracion'] }}
                </div>
            @endif

            @if ($campos['lugar_fecha']['visible'])
                <div class="campo" style="{{ $estiloCampo('lugar_fecha') }}">
                    {{ $campos['lugar_fecha']['texto'] ?: $defecto['lugar_fecha'] }}
                </div>
            @endif

            @if ($plantilla->tipo_certificado === 'generico' && $campos['impartido_por']['visible'])
                <div class="campo" style="{{ $estiloCampo('impartido_por') }}">
                    {{ $campos['impartido_por']['texto'] ?: $defecto['impartido_por'] }}
                </div>
            @endif

            {{-- Firmas --}}
            @if ($mostrarFirma1)
                <div class="campo firma-box" style="{{ $estiloCampo('firma_1') }}">
                    <img src="{{ storage_path('app/public/' . $plantilla->firma_1) }}" class="firma-img"
                        alt="Firma 1">
                    <div class="firma-linea"></div>
                    <p class="firma-nombre">{{ $plantilla->nombre_firma_1 }}</p>
                </div>
            @endif

            @if ($mostrarFirma2)
                <div class="campo firma-box" style="{{ $estiloCampo('firma_2') }}">
                    <img src="{{ storage_path('app/public/' . $plantilla->firma_2) }}" class="firma-img"
                        alt="Firma 2">
                    <div class="firma-linea"></div>
                    <p class="firma-nombre">{{ $plantilla->nombre_firma_2 }}</p>
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
