<?php

namespace Tests\Feature;

use App\Models\Capacitacion;
use App\Models\Participante;
use App\Models\Plantilla;
use App\Services\DiplomaCamposService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Cubre un bug real: las fuentes Visby (.otf con contorno CFF) nunca se
 * aplicaban en el PDF final porque Dompdf no puede leer ese formato, y
 * config/dompdf.php tenía una estructura que el paquete ni siquiera leía
 * (todo fuera de la clave 'options'). El PDF siempre caía a Helvetica/Times
 * sin avisar, aunque el editor visual sí mostrara la fuente correcta.
 */
class DiplomaFuentesTest extends TestCase
{
    use RefreshDatabase;

    private const PIXEL_PNG_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

    public function test_todas_las_fuentes_visby_tienen_un_ttf_registrable_por_dompdf(): void
    {
        foreach (DiplomaCamposService::FUENTES_PDF_ARCHIVO as $familia => $archivo) {
            $ruta = base_path("resources/fonts/VisbyCF-ttf/{$archivo}");
            $this->assertFileExists($ruta, "Falta el .ttf de {$familia}");

            $magic = file_get_contents($ruta, false, null, 0, 4);
            $this->assertNotSame(
                'OTTO',
                $magic,
                "{$archivo} sigue siendo CFF/OpenType (Dompdf no puede leerlo); debe convertirse a TrueType con otf2ttf"
            );
        }
    }

    public function test_el_pdf_generado_incrusta_la_fuente_visby_seleccionada_y_no_cae_a_helvetica(): void
    {
        $capacitacion = Capacitacion::create([
            'nombre' => 'Curso de prueba de fuentes',
            'lugar' => 'Auditorio',
            'fecha' => now()->toDateString(),
            'impartido_por' => 'CCISUR',
            'cupos' => 'ilimitado',
            'medio' => 'gratis',
        ]);

        $fondoPath = 'fondos/test-fuentes-' . $capacitacion->id . '.png';
        Storage::disk('public')->put($fondoPath, base64_decode(self::PIXEL_PNG_BASE64));

        $plantilla = Plantilla::create([
            'capacitacion_id' => $capacitacion->id,
            'fondo' => $fondoPath,
            'fondo_width' => 800,
            'fondo_height' => 600,
            'fecha_emision' => now()->toDateString(),
            'orientacion' => 'horizontal',
            'tipo_certificado' => 'generico',
        ]);

        $participante = Participante::factory()->create([
            'nombre_completo' => 'José Ramón Núñez',
        ]);

        $pdf = Pdf::loadView('pdf.diplomas', [
            'participantes' => collect([$participante]),
            'plantilla' => $plantilla,
            'capacitacion' => $capacitacion,
        ]);
        $contenido = $pdf->output();

        preg_match_all('/\/BaseFont\s*\/([A-Za-z0-9+\-]+)/', $contenido, $coincidencias);
        $fuentesIncrustadas = array_unique($coincidencias[1]);

        // Con el subsetting de fuentes activado, Dompdf antepone un prefijo
        // de subconjunto al nombre (p. ej. "SUBAAC+VisbyCF-Heavy"), así que
        // se compara por coincidencia parcial en vez de por igualdad exacta.
        $contieneVisbyHeavy = collect($fuentesIncrustadas)->contains(fn ($f) => str_contains($f, 'VisbyCF-Heavy'));
        $contieneHelveticaBold = collect($fuentesIncrustadas)->contains(fn ($f) => str_contains($f, 'Helvetica-Bold'));

        // "nombre" usa visby-heavy + bold por defecto: si no se registró la
        // variante bold, Dompdf sustituye todo el campo por Helvetica-Bold.
        $this->assertTrue($contieneVisbyHeavy, 'Se esperaba VisbyCF-Heavy incrustada, se encontró: ' . implode(', ', $fuentesIncrustadas));
        $this->assertFalse($contieneHelveticaBold, 'El campo "nombre" cayó a Helvetica-Bold en vez de usar Visby');
    }
}
