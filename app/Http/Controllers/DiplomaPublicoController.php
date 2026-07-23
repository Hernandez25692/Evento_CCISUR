<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participante;
use App\Models\Capacitacion;
use App\Services\DiplomaCamposService;
use App\Services\VerificacionDiplomaService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class DiplomaPublicoController extends Controller
{
    public function index()
    {
        return view('publico.verificar_diploma');
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'identidad' => 'required|string|max:50',
        ]);

        $participante = Participante::where('identidad', $request->identidad)->first();

        if (!$participante) {
            return back()->with('error', 'No se encontró ningún participante con esa identidad.');
        }

        // Filtrar capacitaciones donde esté habilitado
        $capacitaciones = $participante->capacitaciones()
            ->withPivot('habilitado_diploma')
            ->wherePivot('habilitado_diploma', true)
            ->get();


        return view('publico.verificar_diploma', compact('participante', 'capacitaciones'));
    }


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

    public function descargar($capacitacion_id, $identidad)
    {
        $participante = Participante::where('identidad', $identidad)->firstOrFail();

        // Verificar que está habilitado
        $capacitacion = $participante->capacitaciones()
            ->where('capacitacion_id', $capacitacion_id)
            ->wherePivot('habilitado_diploma', true)
            ->firstOrFail();

        $pdf = PDF::loadView('diplomas.plantilla_pdf', compact('participante', 'capacitacion'));
        return $pdf->download('Diploma_' . $participante->nombre_completo . '.pdf');
    }
}
