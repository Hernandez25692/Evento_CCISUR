<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use App\Models\Capacitacion;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificadoController extends Controller
{
    public function buscar()
    {
        return view('certificados.buscar');
    }

    public function resultado(Request $request)
    {
        $request->validate([
            'identidad' => 'required|string|max:50'
        ]);

        $participante = Participante::with('capacitaciones')
            ->where('identidad', $request->identidad)
            ->first();

        return view('certificados.resultado', compact('participante'));
    }

    public function descargar($capacitacion_id, $participante_id)
    {
        $capacitacion = Capacitacion::with('plantilla')->findOrFail($capacitacion_id);

        // Verificamos que el participante esté vinculado a la capacitación
        $participante = Participante::where('id', $participante_id)
            ->whereHas('capacitaciones', function ($query) use ($capacitacion_id) {
                $query->where('capacitacion_id', $capacitacion_id);
            })->first();

        if (!$participante) {
            return redirect()->route('certificados.buscar')->with('error', '❌ Participante no encontrado en esta capacitación.');
        }

        $plantilla = $capacitacion->plantilla;

        if (!$plantilla || !$plantilla->fondo) {
            return redirect()->route('certificados.buscar')->with('error', '❌ Esta capacitación no tiene plantilla de diploma configurada.');
        }

        // Usamos la misma vista que en la vista previa, pero con un solo participante
        $participantes = collect([$participante]);

        $pdf = Pdf::loadView('pdf.diplomas', compact('participantes', 'plantilla', 'capacitacion'))
            ->setPaper('letter', $plantilla->orientacion == 'vertical' ? 'portrait' : 'landscape');

        return $pdf->download("Diploma_{$participante->identidad}.pdf");
    }
}
