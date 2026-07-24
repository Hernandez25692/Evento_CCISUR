<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use App\Models\Capacitacion;
use App\Services\DiplomaCamposService;
use App\Services\VerificacionDiplomaService;

class DiplomaPublicoController extends Controller
{
    /**
     * Página pública a la que redirige el QR impreso en el diploma. Muestra
     * si el certificado es auténtico y, de serlo, los mismos textos que
     * aparecen impresos (misma fuente de verdad que usa el PDF:
     * DiplomaCamposService), para que lo mostrado aquí nunca se desincronice
     * de lo que realmente dice el certificado.
     */
    public function verificar(string $codigo)
    {
        $registro = VerificacionDiplomaService::localizar($codigo);

        if (!$registro || !$registro['habilitado_diploma']) {
            return view('publico.verificar_certificado', ['valido' => false]);
        }

        $capacitacion = Capacitacion::with('plantilla')->find($registro['capacitacion_id']);
        $participante = Participante::find($registro['participante_id']);
        $plantilla = $capacitacion?->plantilla;

        if (!$capacitacion || !$participante || !$plantilla) {
            return view('publico.verificar_certificado', ['valido' => false]);
        }

        $campos = DiplomaCamposService::resolve($plantilla->campos);
        $defecto = DiplomaCamposService::contenidoPorDefecto($capacitacion, $plantilla);
        $texto = fn(string $clave) => $campos[$clave]['texto'] ?: $defecto[$clave];

        return view('publico.verificar_certificado', [
            'valido' => true,
            'participante' => $participante,
            'capacitacion' => $capacitacion,
            'actividad' => $texto('actividad'),
            'modalidadDuracion' => $texto('modalidad_duracion'),
            'lugarFecha' => $texto('lugar_fecha'),
            'impartidoPor' => $texto('impartido_por'),
        ]);
    }
}
