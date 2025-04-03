<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participante;
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

        $capacitaciones = $participante->capacitaciones;

        return view('publico.verificar_diploma', compact('participante', 'capacitaciones'));
    }

    public function descargar($capacitacion_id, $identidad)
    {
        $participante = Participante::where('identidad', $identidad)->firstOrFail();
        $capacitacion = $participante->capacitaciones()->where('capacitacion_id', $capacitacion_id)->firstOrFail();

        $pdf = PDF::loadView('diplomas.plantilla_pdf', compact('participante', 'capacitacion'));
        return $pdf->download('Diploma_' . $participante->nombre_completo . '.pdf');
    }
}
