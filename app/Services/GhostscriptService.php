<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use RuntimeException;

/**
 * Convierte PDF a PNG llamando a Ghostscript por línea de comandos, en vez
 * de depender de la extensión PHP Imagick (que además, por debajo, delega
 * la lectura de PDFs en Ghostscript de todos modos).
 */
class GhostscriptService
{
    /**
     * Renderiza la primera página de un PDF como PNG a la resolución dada.
     */
    public static function pdfAPng(string $pdfPath, string $pngPath, int $dpi = 300): void
    {
        $binario = self::binario();

        $resultado = Process::run([
            $binario,
            '-dSAFER',
            '-dBATCH',
            '-dNOPAUSE',
            '-dFirstPage=1',
            '-dLastPage=1',
            '-sDEVICE=png16m',
            "-r{$dpi}",
            "-sOutputFile={$pngPath}",
            $pdfPath,
        ]);

        if ($resultado->failed()) {
            throw new RuntimeException(
                "Ghostscript no pudo convertir el PDF a PNG ({$pdfPath}): " . $resultado->errorOutput()
            );
        }
    }

    /**
     * Ubica el ejecutable de Ghostscript en el sistema. Permite fijarlo por
     * configuración (variable de entorno GHOSTSCRIPT_BINARY) para no
     * depender de que esté en el PATH en el servidor de producción.
     */
    protected static function binario(): string
    {
        $configurado = env('GHOSTSCRIPT_BINARY');
        if ($configurado && (self::esEjecutable($configurado) || self::comandoDisponible($configurado))) {
            return $configurado;
        }

        $candidatos = PHP_OS_FAMILY === 'Windows'
            ? ['gswin64c', 'gswin32c']
            : ['gs'];

        foreach ($candidatos as $candidato) {
            if (self::comandoDisponible($candidato)) {
                return $candidato;
            }
        }

        if (PHP_OS_FAMILY === 'Windows') {
            foreach (self::rutasWindowsComunes() as $ruta) {
                if (self::esEjecutable($ruta)) {
                    return $ruta;
                }
            }
        }

        throw new RuntimeException(
            'No se encontró Ghostscript instalado. Instálalo desde https://ghostscript.com/releases/gsdnld.html ' .
            '(en Windows, agrega la carpeta "bin" al PATH o define GHOSTSCRIPT_BINARY en el archivo .env con la ' .
            'ruta completa al ejecutable gswin64c.exe) y vuelve a intentarlo.'
        );
    }

    protected static function comandoDisponible(string $comando): bool
    {
        return Process::run([$comando, '--version'])->successful();
    }

    protected static function esEjecutable(string $ruta): bool
    {
        return is_file($ruta) && is_executable($ruta);
    }

    /**
     * @return string[]
     */
    protected static function rutasWindowsComunes(): array
    {
        $rutas = [];
        foreach (['C:/Program Files/gs/*/bin/gswin64c.exe', 'C:/Program Files (x86)/gs/*/bin/gswin32c.exe'] as $patron) {
            $rutas = array_merge($rutas, glob($patron) ?: []);
        }

        // Si hay varias versiones instaladas, se prefiere la más reciente
        // (orden alfabético del nombre de carpeta, p. ej. "gs10.03.1").
        rsort($rutas);

        return $rutas;
    }
}
