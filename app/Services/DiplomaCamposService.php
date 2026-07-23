<?php

namespace App\Services;

class DiplomaCamposService
{
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
     * porque la misma fuente está registrada con nombres diferentes en
     * config/dompdf.php (para el PDF) y en public/css/fonts-visby.css
     * (para el navegador) — la clave lógica evita que se desincronicen.
     */
    public const FUENTES = [
        'visby-light' => ['label' => 'Visby Light', 'pdf' => 'Visby-Light', 'web' => 'VisbyCF-Light'],
        'visby-demibold' => ['label' => 'Visby DemiBold', 'pdf' => 'Visby-DemiBold', 'web' => 'VisbyCF-DemiBold'],
        'visby-heavy' => ['label' => 'Visby Heavy', 'pdf' => 'Visby-Heavy', 'web' => 'VisbyCF-Heavy'],
        'helvetica' => ['label' => 'Helvetica (genérica)', 'pdf' => 'Helvetica, sans-serif', 'web' => 'Helvetica, Arial, sans-serif'],
        'times' => ['label' => 'Times (genérica)', 'pdf' => 'Times, serif', 'web' => "'Times New Roman', Times, serif"],
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
        return [
            'titulo_secundario'  => ['x' => 50, 'y' => 20, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-demibold', 'bold' => false, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => ''],
            'nombre'              => ['x' => 50, 'y' => 34, 'align' => 'center', 'font_size' => 30, 'font_family' => 'visby-heavy', 'bold' => true, 'underline' => true, 'visible' => true, 'color' => '#004aad', 'texto' => ''],
            'participacion'       => ['x' => 50, 'y' => 42, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-light', 'bold' => false, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => ''],
            'actividad'           => ['x' => 50, 'y' => 47, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-heavy', 'bold' => true, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => ''],
            'modalidad_duracion'  => ['x' => 50, 'y' => 52, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-light', 'bold' => false, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => ''],
            'lugar_fecha'         => ['x' => 50, 'y' => 57, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-light', 'bold' => false, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => ''],
            'impartido_por'       => ['x' => 50, 'y' => 62, 'align' => 'center', 'font_size' => 20, 'font_family' => 'visby-light', 'bold' => true, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => ''],
            'firma_1'             => ['x' => 30, 'y' => 88, 'align' => 'center', 'font_size' => 16, 'font_family' => 'visby-demibold', 'bold' => true, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => ''],
            'firma_2'             => ['x' => 70, 'y' => 88, 'align' => 'center', 'font_size' => 16, 'font_family' => 'visby-demibold', 'bold' => true, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => ''],
            // 'font_size' se reutiliza como el tamaño en píxeles del cuadro
            // del QR (mismo patrón que el ancho fijo de las firmas, pero
            // configurable como cualquier otro campo).
            'qr_verificacion'     => ['x' => 88, 'y' => 90, 'align' => 'center', 'font_size' => 90, 'font_family' => 'visby-light', 'bold' => false, 'underline' => false, 'visible' => true, 'color' => '#000000', 'texto' => ''],
        ];
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
                    ? $valores['align']
                    : 'center',
                'font_size' => max(8, min(200, (int) ($valores['font_size'] ?? 20))),
                'font_family' => array_key_exists($valores['font_family'] ?? null, self::FUENTES)
                    ? $valores['font_family']
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
