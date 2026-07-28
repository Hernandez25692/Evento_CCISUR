<?php

namespace Tests\Unit;

use App\Services\DiplomaCamposService;
use PHPUnit\Framework\TestCase;

class DiplomaCamposServiceTest extends TestCase
{
    public function test_con_salto_linea_desactivado_no_toca_el_texto(): void
    {
        $this->assertSame(
            'Ana Maria Lopez',
            DiplomaCamposService::conSaltoLinea('Ana Maria Lopez', 0)
        );
    }

    public function test_con_salto_linea_parte_despues_de_la_palabra_indicada(): void
    {
        $this->assertSame(
            "Ana\nMaria Lopez Fuentes",
            DiplomaCamposService::conSaltoLinea('Ana Maria Lopez Fuentes', 1)
        );

        $this->assertSame(
            "Ana Maria\nLopez Fuentes",
            DiplomaCamposService::conSaltoLinea('Ana Maria Lopez Fuentes', 2)
        );
    }

    public function test_con_salto_linea_fuera_de_rango_no_toca_el_texto(): void
    {
        $this->assertSame(
            'Ana Maria',
            DiplomaCamposService::conSaltoLinea('Ana Maria', 5)
        );
    }

    public function test_defaults_incluye_las_propiedades_tipograficas_nuevas_en_todos_los_campos(): void
    {
        foreach (DiplomaCamposService::defaults() as $clave => $campo) {
            foreach (['line_height', 'max_width', 'letter_spacing', 'italic', 'rotacion', 'salto_linea_palabra'] as $propiedad) {
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
                'salto_linea_palabra' => -5,
            ],
        ]);

        $this->assertEquals(3, $limpio['nombre']['line_height']);
        $this->assertEquals(10, $limpio['nombre']['max_width']);
        $this->assertEquals(30, $limpio['nombre']['letter_spacing']);
        $this->assertEquals(180, $limpio['nombre']['rotacion']);
        $this->assertSame(0, $limpio['nombre']['salto_linea_palabra']);
    }
}
