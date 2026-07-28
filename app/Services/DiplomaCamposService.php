<?php

namespace App\Services;

class DiplomaCamposService
{
    /**
     * Bloque @font-face con las fuentes Visby para incrustar en el <style>
     * de cualquier vista que genere un PDF de diploma (pdf/diplomas.blade.php
     * y cualquier otra que se agregue). Única fuente de verdad: si se agrega
     * un peso nuevo, se registra aquí una sola vez y todas las vistas de PDF
     * lo heredan automáticamente.
     */
    public static function fontFacesPdf(): string
    {
        $reglas = '';

        foreach (self::FUENTES_PDF_ARCHIVO as $familia => $archivo) {
            $ruta = base_path("resources/fonts/VisbyCF-ttf/{$archivo}");
            // Se registra igual para normal y bold (mismo archivo): el editor
            // permite activar "negrita" sobre cualquier peso Visby, pero no
            // existe un archivo "bold" separado de cada peso. Sin esta
            // segunda regla, Dompdf no encuentra variante bold registrada y
            // sustituye el campo entero por Helvetica-Bold en vez de negritar
            // la misma tipografía Visby.
            $reglas .= "@font-face { font-family: '{$familia}'; src: url('{$ruta}') format('truetype'); font-weight: normal; }\n";
            $reglas .= "@font-face { font-family: '{$familia}'; src: url('{$ruta}') format('truetype'); font-weight: bold; }\n";
        }

        return $reglas;
    }


    /**
     * Claves de campos posicionables sobre la plantilla del diploma.
     * Esta lista es la única fuente de verdad: la usan tanto el editor
     * visual de posiciones como la vista que genera el PDF final.
     */
    public const CAMPOS = [
        'titulo_secundario',
        'nombre',
        'participacion',
        'actividad',
        'modalidad_duracion',
        'lugar_fecha',
        'impartido_por',
        'firma_1',
        'firma_2',
        'qr_verificacion',
    ];

    /**
     * Etiquetas legibles para mostrar en el editor visual.
     */
    public const ETIQUETAS = [
        'titulo_secundario' => 'Título / texto introductorio',
        'nombre' => 'Nombre del participante',
        'participacion' => 'Texto de participación',
        'actividad' => 'Nombre de la capacitación',
        'modalidad_duracion' => 'Modalidad y duración',
        'lugar_fecha' => 'Lugar y fecha',
        'impartido_por' => 'Impartido por',
        'firma_1' => 'Firma 1 (imagen y nombre)',
        'firma_2' => 'Firma 2 (imagen y nombre)',
        'qr_verificacion' => 'Código QR de verificación',
    ];

    /**
     * Fuentes permitidas por campo. Los nombres "pdf"/"web" son distintos
     * porque la misma fuente está registrada con nombres diferentes en el
     * PDF (@font-face en resources/views/pdf/diplomas.blade.php, usando los
     * .ttf de resources/fonts/VisbyCF-ttf) y en el navegador
     * (public/css/fonts-visby.css, usando los .otf originales de
     * public/fonts/VisbyCF) — la clave lógica evita que se desincronicen.
     *
     * Los .ttf del PDF son una conversión de los .otf originales (que usan
     * contorno CFF/PostScript, formato que Dompdf no sabe leer) a contorno
     * TrueType, generada con la herramienta `otf2ttf`. Visualmente son la
     * misma tipografía; solo cambia el formato del contorno.
     */
    public const FUENTES = [
        'visby-thin' => ['label' => 'Visby Thin', 'pdf' => 'Visby-Thin', 'web' => 'VisbyCF-Thin'],
        'visby-light' => ['label' => 'Visby Light', 'pdf' => 'Visby-Light', 'web' => 'VisbyCF-Light'],
        'visby-medium' => ['label' => 'Visby Medium', 'pdf' => 'Visby-Medium', 'web' => 'VisbyCF-Medium'],
        'visby-demibold' => ['label' => 'Visby DemiBold', 'pdf' => 'Visby-DemiBold', 'web' => 'VisbyCF-DemiBold'],
        'visby-bold' => ['label' => 'Visby Bold', 'pdf' => 'Visby-Bold', 'web' => 'VisbyCF-Bold'],
        'visby-extrabold' => ['label' => 'Visby ExtraBold', 'pdf' => 'Visby-ExtraBold', 'web' => 'VisbyCF-ExtraBold'],
        'visby-heavy' => ['label' => 'Visby Heavy', 'pdf' => 'Visby-Heavy', 'web' => 'VisbyCF-Heavy'],
        'helvetica' => ['label' => 'Helvetica (genérica)', 'pdf' => 'Helvetica, sans-serif', 'web' => 'Helvetica, Arial, sans-serif'],
        'times' => ['label' => 'Times (genérica)', 'pdf' => 'Times, serif', 'web' => "'Times New Roman', Times, serif"],
    ];

    /**
     * Fuentes Visby que deben registrarse como @font-face al generar el PDF
     * (las genéricas Helvetica/Times ya las conoce Dompdf de fábrica).
     * Clave = nombre de familia usado en el PDF, valor = archivo .ttf en
     * resources/fonts/VisbyCF-ttf.
     */
    public const FUENTES_PDF_ARCHIVO = [
        'Visby-Thin' => 'VisbyCF-Thin.ttf',
        'Visby-Light' => 'VisbyCF-Light.ttf',
        'Visby-Medium' => 'VisbyCF-Medium.ttf',
        'Visby-DemiBold' => 'VisbyCF-DemiBold.ttf',
        'Visby-Bold' => 'VisbyCF-Bold.ttf',
        'Visby-ExtraBold' => 'VisbyCF-ExtraBold.ttf',
        'Visby-Heavy' => 'VisbyCF-Heavy.ttf',
    ];

    /**
     * Posiciones y estilos por defecto (porcentaje del lienzo) calibrados
     * para reproducir el layout fijo que el sistema usaba antes de que las
     * posiciones/estilos fueran configurables. Cualquier plantilla sin
     * "campos" guardados se sigue viendo casi igual que antes de este
     * cambio (única diferencia intencional: "nombre" pasa de una caja con
     * borde inferior a texto subrayado, ahora que el subrayado es un
     * control genérico reusable en cualquier campo).
     */
    public static function defaults(): array
    {
        // Propiedades comunes a todo campo de texto; se combinan con las
        // específicas de cada uno más abajo. Mantener esto en un solo lugar
        // es lo que permite agregar una propiedad nueva (p. ej. rotación) y
        // que aplique automáticamente a los diez campos sin repetirla.
        $baseTexto = [
            'line_height' => 1.4,
            'max_width' => 80,
            'letter_spacing' => 0,
            'italic' => false,
            'rotacion' => 0,
            // Fuerza un salto de línea manual después de la palabra N (1 =
            // después de la primera palabra, 0 = desactivado, solo se envuelve
            // automáticamente por ancho). Pensado sobre todo para el nombre
            // del participante: nombre(s) en una línea y apellidos en otra,
            // en vez de depender de que el nombre sea lo bastante largo para
            // envolver solo.
            'salto_linea_palabra' => 0,
        ];

        return [
            'titulo_secundario'  => ['x' => 50, 'y' => 20, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-demibold', 'bold' => false, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => '', ...$baseTexto],
            'nombre'              => ['x' => 50, 'y' => 34, 'align' => 'center', 'font_size' => 30, 'font_family' => 'visby-heavy', 'bold' => true, 'underline' => true, 'visible' => true, 'color' => '#004aad', 'texto' => '', ...$baseTexto],
            'participacion'       => ['x' => 50, 'y' => 42, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-light', 'bold' => false, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => '', ...$baseTexto],
            'actividad'           => ['x' => 50, 'y' => 47, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-heavy', 'bold' => true, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => '', ...$baseTexto],
            'modalidad_duracion'  => ['x' => 50, 'y' => 52, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-light', 'bold' => false, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => '', ...$baseTexto],
            'lugar_fecha'         => ['x' => 50, 'y' => 57, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-light', 'bold' => false, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => '', ...$baseTexto],
            'impartido_por'       => ['x' => 50, 'y' => 62, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-light', 'bold' => true, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => '', ...$baseTexto],
            'firma_1'             => ['x' => 30, 'y' => 88, 'align' => 'center', 'font_size' => 16, 'font_family' => 'visby-demibold', 'bold' => true, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => '', ...$baseTexto],
            'firma_2'             => ['x' => 70, 'y' => 88, 'align' => 'center', 'font_size' => 16, 'font_family' => 'visby-demibold', 'bold' => true, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => '', ...$baseTexto],
            // 'font_size' se reutiliza como el tamaño en píxeles del cuadro
            // del QR (mismo patrón que el ancho fijo de las firmas, pero
            // configurable como cualquier otro campo).
            'qr_verificacion'     => ['x' => 88, 'y' => 90, 'align' => 'center', 'font_size' => 90, 'font_family' => 'visby-light', 'bold' => false, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => '', ...$baseTexto],
        ];
    }

    /**
     * Inserta un salto de línea manual después de la palabra N de un texto
     * (1-indexado). Con $indicePalabra en 0 (o mayor o igual al número de
     * palabras) devuelve el texto sin tocar. Se inserta un "\n" literal, no
     * HTML, así que sigue siendo seguro pasarlo por {{ }} (que escapa HTML
     * pero no toca saltos de línea) — ni el editor ni el PDF necesitan HTML
     * crudo para esto, ambos interpretan "\n" vía white-space: pre-line.
     */
    public static function conSaltoLinea(string $texto, int $indicePalabra): string
    {
        if ($indicePalabra <= 0) {
            return $texto;
        }

        $palabras = preg_split('/\s+/', trim($texto)) ?: [];

        if ($indicePalabra >= count($palabras)) {
            return $texto;
        }

        return implode(' ', array_slice($palabras, 0, $indicePalabra))
            . "\n"
            . implode(' ', array_slice($palabras, $indicePalabra));
    }

    /**
     * Textos que se generan automáticamente a partir de la capacitación y la
     * plantilla cuando el admin no escribió un texto personalizado para ese
     * campo. Única fuente de verdad: la usan tanto el editor (como
     * placeholder/valor por defecto) como la vista que genera el PDF final,
     * así los dos quedan sincronizados por construcción.
     */
    public static function contenidoPorDefecto($capacitacion, $plantilla): array
    {
        \Carbon\Carbon::setLocale('es');
        $fechaFormateada = \Carbon\Carbon::parse($plantilla->fecha_emision)->isoFormat('D [de] MMMM [de] YYYY');

        return [
            'titulo_secundario' => $plantilla->tipo_certificado === 'convenio'
                ? ($plantilla->titulo_convenio ?? '---')
                : 'La Cámara de Comercio e Industrias del Sur otorga el presente certificado de participación a:',
            'participacion' => 'Por su participación en ' . ($capacitacion->tipo_formacion ?? 'virtual') . ':',
            'actividad' => '"' . $capacitacion->nombre . '"',
            'modalidad_duracion' => 'en modalidad ' . ($capacitacion->modalidad ?? 'virtual') . ' con duración de ' . ($capacitacion->duracion ?? 'N horas') . ' horas.',
            'lugar_fecha' => $capacitacion->lugar . ', ' . $fechaFormateada . '.',
            'impartido_por' => 'Impartido por: ' . $capacitacion->impartido_por,
        ];
    }

    /**
     * Combina las posiciones/estilos guardados de una plantilla sobre los
     * valores por defecto, campo por campo, e ignora cualquier clave
     * desconocida. Como el merge es genérico (por clave), cualquier
     * propiedad nueva agregada a defaults() fluye automáticamente sin
     * tener que tocar este método.
     */
    public static function resolve(?array $campos): array
    {
        $resueltos = self::defaults();

        foreach ($campos ?? [] as $clave => $valores) {
            if (!array_key_exists($clave, $resueltos) || !is_array($valores)) {
                continue;
            }

            $resueltos[$clave] = array_merge($resueltos[$clave], array_intersect_key(
                $valores,
                $resueltos[$clave]
            ));
        }

        return $resueltos;
    }

    /**
     * Valida y normaliza el array de campos enviado desde el editor,
     * descartando cualquier clave que no esté en self::CAMPOS.
     */
    public static function sanitize(array $campos): array
    {
        $limpio = [];

        foreach (self::CAMPOS as $clave) {
            if (!isset($campos[$clave]) || !is_array($campos[$clave])) {
                continue;
            }

            $valores = $campos[$clave];
            $limpio[$clave] = [
                'x' => max(0, min(100, (float) ($valores['x'] ?? 0))),
                'y' => max(0, min(100, (float) ($valores['y'] ?? 0))),
                'align' => in_array($valores['align'] ?? 'center', ['left', 'center', 'right'], true)
                    ? $valores['align'] ?? 'center'
                    : 'center',
                'font_size' => max(8, min(200, (int) ($valores['font_size'] ?? 20))),
                'font_family' => array_key_exists($valores['font_family'] ?? null, self::FUENTES)
                    ? $valores['font_family'] ?? 'visby-light'
                    : 'visby-light',
                'bold' => filter_var($valores['bold'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'underline' => filter_var($valores['underline'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'visible' => filter_var($valores['visible'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'color' => preg_match('/^#[0-9a-f]{6}$/i', $valores['color'] ?? '')
                    ? $valores['color']
                    : '#000000',
                'texto' => is_string($valores['texto'] ?? null)
                    ? mb_substr(trim($valores['texto']), 0, 500)
                    : '',
                'line_height' => max(0.8, min(3, (float) ($valores['line_height'] ?? 1.4))),
                'max_width' => max(10, min(100, (float) ($valores['max_width'] ?? 80))),
                'letter_spacing' => max(-5, min(30, (float) ($valores['letter_spacing'] ?? 0))),
                'italic' => filter_var($valores['italic'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'rotacion' => max(-180, min(180, (float) ($valores['rotacion'] ?? 0))),
                'salto_linea_palabra' => max(0, min(20, (int) ($valores['salto_linea_palabra'] ?? 0))),
            ];
        }

        return $limpio;
    }

    /**
     * Calcula el tamaño de página de dompdf a partir de las dimensiones
     * reales de la imagen de fondo, para que el PDF respete su proporción
     * en vez de forzar siempre tamaño "letter". Cuando no hay dimensiones
     * guardadas (plantilla antigua sin re-subir), se cae al tamaño letter
     * de siempre para no romper nada.
     *
     * @return array{size: string|array<int,float>, orientation: string|null}
     */
    public static function paperSize(?int $width, ?int $height): array
    {
        if (!$width || !$height) {
            return ['size' => 'letter', 'orientation' => null];
        }

        // Puntos PDF = píxeles / 96dpi * 72pt/in (96 = dpi asumido del archivo fuente).
        $puntosPorPixel = 72 / 96;

        return [
            'size' => [0, 0, round($width * $puntosPorPixel), round($height * $puntosPorPixel)],
            // El array ya se construye con el ancho/alto reales de la imagen;
            // dompdf voltea width/height si se le pasa 'landscape', así que
            // siempre se pasa 'portrait' junto a un tamaño personalizado.
            'orientation' => 'portrait',
        ];
    }
}
