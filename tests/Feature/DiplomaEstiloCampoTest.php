<?php

namespace Tests\Feature;

use App\Models\Capacitacion;
use App\Models\Participante;
use App\Models\Plantilla;
use App\Services\DiplomaCamposService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Verifica que las propiedades tipográficas nuevas (interlineado, ancho
 * máximo, espaciado entre letras, cursiva, rotación, salto de línea manual)
 * realmente lleguen al HTML que Dompdf convierte a PDF, con los mismos
 * valores que el admin configuró en el editor — es la regresión concreta
 * que motivó separar $estiloCampo/$texto del resto de la plantilla.
 */
class DiplomaEstiloCampoTest extends TestCase
{
    use RefreshDatabase;

    private const PIXEL_PNG_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

    public function test_las_propiedades_tipograficas_configuradas_llegan_al_html_del_pdf(): void
    {
        $capacitacion = Capacitacion::create([
            'nombre' => 'Curso de estilos',
            'lugar' => 'Auditorio',
            'fecha' => now()->toDateString(),
            'impartido_por' => 'CCISUR',
            'cupos' => 'ilimitado',
            'medio' => 'gratis',
        ]);

        $fondoPath = 'fondos/test-estilo-' . $capacitacion->id . '.png';
        Storage::disk('public')->put($fondoPath, base64_decode(self::PIXEL_PNG_BASE64));

        $campos = DiplomaCamposService::defaults();
        $campos['nombre']['salto_linea_palabra'] = 1;
        $campos['actividad']['rotacion'] = 15;
        $campos['actividad']['letter_spacing'] = 4.5;
        $campos['actividad']['italic'] = true;
        $campos['actividad']['line_height'] = 2;
        $campos['actividad']['max_width'] = 55;

        $plantilla = Plantilla::create([
            'capacitacion_id' => $capacitacion->id,
            'fondo' => $fondoPath,
            'fondo_width' => 800,
            'fondo_height' => 600,
            'fecha_emision' => now()->toDateString(),
            'orientacion' => 'horizontal',
            'tipo_certificado' => 'generico',
            'campos' => $campos,
        ]);

        $participante = Participante::factory()->create([
            'nombre_completo' => 'Ana Maria Lopez',
        ]);

        $html = view('pdf.diplomas', [
            'participantes' => collect([$participante]),
            'plantilla' => $plantilla,
            'capacitacion' => $capacitacion,
        ])->render();

        // El nombre debe partirse después de la primera palabra ("Ana").
        $this->assertStringContainsString("Ana\nMaria Lopez", $html);

        // El campo "actividad" debe traer exactamente el estilo configurado.
        $this->assertStringContainsString('rotate(15deg)', $html);
        $this->assertStringContainsString('letter-spacing:4.5px', $html);
        $this->assertStringContainsString('font-style:italic', $html);
        $this->assertStringContainsString('line-height:2', $html);
        $this->assertStringContainsString('max-width:55%', $html);
    }
}
