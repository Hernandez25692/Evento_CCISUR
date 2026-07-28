<?php

namespace Tests\Unit;

use App\Services\DiplomaCamposService;
use PHPUnit\Framework\TestCase;

class DiplomaCamposServiceTest extends TestCase
{
    public function test_defaults_incluye_las_propiedades_tipograficas_nuevas_en_todos_los_campos(): void
    {
        foreach (DiplomaCamposService::defaults() as $clave => $campo) {
            foreach (['line_height', 'max_width', 'letter_spacing', 'italic', 'rotacion'] as $propiedad) {
                $this->assertArrayHasKey($propiedad, $campo, "Falta '{$propiedad}' en el campo '{$clave}'");
            }
        }
    }

    public function test_sanitize_acota_las_propiedades_tipograficas_nuevas_a_rangos_seguros(): void
    {
        $limpio = DiplomaCamposService::sanitize([
            'nombre' => [
                'line_height' => 999,
                'max_width' => -50,
                'letter_spacing' => 999,
                'rotacion' => 999,
            ],
        ]);

        $this->assertEquals(3, $limpio['nombre']['line_height']);
        $this->assertEquals(10, $limpio['nombre']['max_width']);
        $this->assertEquals(30, $limpio['nombre']['letter_spacing']);
        $this->assertEquals(180, $limpio['nombre']['rotacion']);
    }
}
