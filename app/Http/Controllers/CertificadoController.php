<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use App\Models\Capacitacion;
use Illuminate\Support\Facades\Storage;
use App\Services\DiplomaCamposService;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificadoController extends Controller
{
    public function buscar()
    {
        return view('certificados.buscar');
    }

    public function validarQR()
    {
        return view('validar_qr');
    }

    /**
     * Muestra el formulario para buscar un participante por su identidad.
     
     */
    public function resultado(Request $request)
    {
        $request->validate([
            'identidad' => 'required|string|max:50'
        ]);

        $participante = Participante::where('identidad', $request->identidad)
            ->with(['capacitaciones' => function ($q) {
                $q->withPivot('habilitado_diploma');
            }])
            ->first();

        return view('certificados.resultado', compact('participante'));
    }

    public function descargar($capacitacion_id, $participante_id)
    {
        $capacitacion = Capacitacion::with('plantilla')->findOrFail($capacitacion_id);

        // Verificamos que el participante esté vinculado a la capacitación
        // y que el diploma esté habilitado para descarga (mismo criterio que
        // usa la vista pública para mostrar el botón de descarga).
        $participante = Participante::where('id', $participante_id)
            ->whereHas('capacitaciones', function ($query) use ($capacitacion_id) {
                $query->where('capacitacion_id', $capacitacion_id)
                    ->where('habilitado_diploma', true);
            })->first();

        if (!$participante) {
            return redirect()->route('certificados.buscar')->with('error', '❌ Participante no encontrado o diploma no habilitado para esta capacitación.');
        }

        $plantilla = $capacitacion->plantilla;

        if (!$plantilla || !$plantilla->fondo) {
            return redirect()->route('certificados.buscar')->with('error', '❌ Esta capacitación no tiene plantilla de diploma configurada.');
        }

        // Usamos la misma vista que en la vista previa, pero con un solo participante
        $participantes = collect([$participante]);

        $papel = DiplomaCamposService::paperSize($plantilla->fondo_width, $plantilla->fondo_height);
        $orientacion = $papel['orientation'] ?? ($plantilla->orientacion == 'vertical' ? 'portrait' : 'landscape');

        $pdf = Pdf::loadView('pdf.diplomas', compact('participantes', 'plantilla', 'capacitacion'))
            ->setPaper($papel['size'], $orientacion);

        return $pdf->download("Diploma_{$participante->identidad}.pdf");
    }
}
