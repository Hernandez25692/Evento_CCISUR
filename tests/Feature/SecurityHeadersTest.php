<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * El CSP del middleware SecurityHeaders bloqueó por completo Alpine.js (usa
 * Function() para evaluar x-data, lo que requiere 'unsafe-eval') sin lanzar
 * ningún error visible — solo un warning en consola del navegador. El
 * editor de posiciones del diploma quedó con su estado a medio inicializar
 * durante días antes de que se detectara la causa real. Este test evita que
 * la directiva se vuelva a quitar sin darse cuenta.
 */
class SecurityHeadersTest extends TestCase
{
    public function test_el_csp_permite_unsafe_eval_para_que_alpine_js_funcione(): void
    {
        $response = $this->get('/login');

        $csp = $response->headers->get('Content-Security-Policy');

        $this->assertNotNull($csp);
        $this->assertMatchesRegularExpression(
            "/script-src[^;]*'unsafe-eval'/",
            $csp,
            "El CSP debe incluir 'unsafe-eval' en script-src o Alpine.js deja de funcionar (silenciosamente)."
        );
    }
}
