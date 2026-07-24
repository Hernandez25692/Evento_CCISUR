<?php

namespace Tests\Feature;

use App\Models\Capacitacion;
use App\Models\DescargaDiplomaLog;
use App\Models\Participante;
use App\Models\Plantilla;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificadoDescargaTest extends TestCase
{
    use RefreshDatabase;

    private const PIXEL_PNG_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

    private function crearCapacitacionConPlantilla(bool $diplomasPublicados = false): Capacitacion
    {
        $capacitacion = Capacitacion::create([
            'nombre' => 'Curso de prueba',
            'lugar' => 'Auditorio',
            'fecha' => now()->toDateString(),
            'impartido_por' => 'CCISUR',
            'cupos' => 'ilimitado',
            'medio' => 'gratis',
        ]);

        // diplomas_publicados no es mass-assignable a propósito (solo se
        // activa vía CapacitacionController::publicarDiplomas), así que se
        // fija igual que lo haría ese método: asignación directa + save().
        $capacitacion->diplomas_publicados = $diplomasPublicados;
        $capacitacion->save();

        $fondoPath = 'fondos/test-' . $capacitacion->id . '.png';
        Storage::disk('public')->put($fondoPath, base64_decode(self::PIXEL_PNG_BASE64));

        Plantilla::create([
            'capacitacion_id' => $capacitacion->id,
            'fondo' => $fondoPath,
            'fondo_width' => 800,
            'fondo_height' => 600,
            'fecha_emision' => now()->toDateString(),
            'orientacion' => 'horizontal',
            'tipo_certificado' => 'generico',
        ]);

        return $capacitacion->fresh('plantilla');
    }

    public function test_no_descarga_si_los_diplomas_no_estan_publicados(): void
    {
        $capacitacion = $this->crearCapacitacionConPlantilla(false);
        $participante = Participante::factory()->create();
        $participante->capacitaciones()->attach($capacitacion->id, ['habilitado_diploma' => true]);

        $response = $this->get(route('certificados.descargar', [$capacitacion->id, $participante->identidad]));

        $response->assertRedirect(route('certificados.buscar'));
        $this->assertSame(0, DescargaDiplomaLog::count());
    }

    public function test_no_descarga_si_el_participante_no_esta_habilitado(): void
    {
        $capacitacion = $this->crearCapacitacionConPlantilla(true);
        $participante = Participante::factory()->create();
        $participante->capacitaciones()->attach($capacitacion->id, ['habilitado_diploma' => false]);

        $response = $this->get(route('certificados.descargar', [$capacitacion->id, $participante->identidad]));

        $response->assertRedirect(route('certificados.buscar'));
    }

    public function test_no_descarga_con_la_identidad_de_otro_participante(): void
    {
        $capacitacion = $this->crearCapacitacionConPlantilla(true);
        $dueño = Participante::factory()->create();
        $dueño->capacitaciones()->attach($capacitacion->id, ['habilitado_diploma' => true]);
        $otro = Participante::factory()->create();

        // Alguien que conoce el id numérico interno pero no la identidad real
        // del dueño del diploma no debe poder descargarlo.
        $response = $this->get(route('certificados.descargar', [$capacitacion->id, $otro->identidad]));

        $response->assertRedirect(route('certificados.buscar'));
    }

    public function test_descarga_el_pdf_y_registra_auditoria_cuando_todo_esta_en_regla(): void
    {
        $capacitacion = $this->crearCapacitacionConPlantilla(true);
        $participante = Participante::factory()->create();
        $participante->capacitaciones()->attach($capacitacion->id, ['habilitado_diploma' => true]);

        $response = $this->get(route('certificados.descargar', [$capacitacion->id, $participante->identidad]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');

        $this->assertSame(1, DescargaDiplomaLog::count());
        $log = DescargaDiplomaLog::first();
        $this->assertSame($capacitacion->id, $log->capacitacion_id);
        $this->assertSame($participante->id, $log->participante_id);
    }

    public function test_la_segunda_descarga_reutiliza_el_pdf_cacheado(): void
    {
        $capacitacion = $this->crearCapacitacionConPlantilla(true);
        $participante = Participante::factory()->create();
        $participante->capacitaciones()->attach($capacitacion->id, ['habilitado_diploma' => true]);

        $primera = $this->get(route('certificados.descargar', [$capacitacion->id, $participante->identidad]));
        $segunda = $this->get(route('certificados.descargar', [$capacitacion->id, $participante->identidad]));

        $primera->assertOk();
        $segunda->assertOk();
        // Ambas descargas deben servir exactamente el mismo archivo cacheado.
        $this->assertSame($primera->getFile()->getRealPath(), $segunda->getFile()->getRealPath());
        $this->assertSame(2, DescargaDiplomaLog::count());
    }
}
