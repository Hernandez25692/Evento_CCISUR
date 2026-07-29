<?php

namespace Tests\Feature;

use App\Imports\ParticipantesImport;
use App\Models\Capacitacion;
use App\Models\Participante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * Antes de esta corrección, una fila del Excel cuyo correo ya pertenecía a
 * otro participante hacía que Participante::firstOrCreate() lanzara
 * UniqueConstraintViolationException sin capturar, tumbando el import
 * completo con un 500 en vez de reportar solo esa fila.
 */
class ParticipanteImportTest extends TestCase
{
    use RefreshDatabase;

    private function fila(array $valores): array
    {
        // identidad, nombre, correo, telefono, empresa, puesto, edad,
        // nivel_educativo, genero, municipio, ciudad
        return array_pad($valores, 11, '');
    }

    public function test_fila_con_correo_duplicado_no_tumba_el_import_completo(): void
    {
        $capacitacion = Capacitacion::create([
            'nombre' => 'Curso de prueba de import',
            'lugar' => 'Auditorio',
            'fecha' => now()->toDateString(),
            'impartido_por' => 'CCISUR',
            'cupos' => 'ilimitado',
        ]);

        Participante::factory()->create([
            'identidad' => '0801199900001',
            'correo' => 'existente@correo.com',
        ]);

        $rows = new Collection([
            1 => $this->fila(['Encabezado']), // fila de encabezado, se ignora
            2 => $this->fila([
                '0801199900002', 'Juan Pérez', 'existente@correo.com', '99999999',
                '', '', '25', 'Universitaria Completa', 'Masculino', 'Tegucigalpa', 'Tegucigalpa',
            ]),
            3 => $this->fila([
                '0801199900003', 'María López', 'maria@correo.com', '88888888',
                '', '', '30', 'Universitaria Completa', 'Femenino', 'Tegucigalpa', 'Tegucigalpa',
            ]),
        ]);

        $import = new ParticipantesImport($capacitacion->id);
        $import->collection($rows);

        $this->assertNull($import->errorGeneral);
        $this->assertCount(1, $import->errores);
        $this->assertStringContainsString('existente@correo.com', $import->errores[0]);
        $this->assertEquals(1, $import->importados);

        $this->assertDatabaseMissing('participantes', ['identidad' => '0801199900002']);
        $this->assertDatabaseHas('participantes', ['identidad' => '0801199900003', 'correo' => 'maria@correo.com']);
    }
}
