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
    ];

    /**
     * Posiciones por defecto (porcentaje del lienzo) calibradas para
     * reproducir el layout fijo que el sistema usaba antes de que las
     * posiciones fueran configurables. Cualquier plantilla sin "campos"
     * guardados se sigue viendo igual que antes de este cambio.
     */
    public static function defaults(): array
    {
        return [
            'titulo_secundario'  => ['x' => 50, 'y' => 20, 'align' => 'center', 'font_size' => 20],
            'nombre'              => ['x' => 50, 'y' => 34, 'align' => 'center', 'font_size' => 30],
            'participacion'       => ['x' => 50, 'y' => 42, 'align' => 'center', 'font_size' => 20],
            'actividad'           => ['x' => 50, 'y' => 47, 'align' => 'center', 'font_size' => 20],
            'modalidad_duracion'  => ['x' => 50, 'y' => 52, 'align' => 'center', 'font_size' => 20],
            'lugar_fecha'         => ['x' => 50, 'y' => 57, 'align' => 'center', 'font_size' => 20],
            'impartido_por'       => ['x' => 50, 'y' => 62, 'align' => 'center', 'font_size' => 20],
            'firma_1'             => ['x' => 30, 'y' => 88, 'align' => 'center', 'font_size' => 16],
            'firma_2'             => ['x' => 70, 'y' => 88, 'align' => 'center', 'font_size' => 16],
        ];
    }

    /**
     * Combina las posiciones guardadas de una plantilla sobre los valores
     * por defecto, campo por campo, e ignora cualquier clave desconocida.
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
                'font_size' => max(8, min(80, (int) ($valores['font_size'] ?? 20))),
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
