<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantilla;
use App\Models\Capacitacion;
use App\Models\Participante;
use Barryvdh\DomPDF\Facade\Pdf;

class PlantillaDiplomaController extends Controller
{
    public function vistaPrevia($capacitacion_id, $plantilla_id)
    {
        $capacitacion = Capacitacion::findOrFail($capacitacion_id);
        $participante = $capacitacion->participantes()->first();
        $plantilla = Plantilla::findOrFail($plantilla_id);

        if (!$participante) {
            return redirect()->back()->with('error', 'No hay participantes en esta capacitación para previsualizar el diploma.');
        }

        $pdf = Pdf::loadView('diplomas.plantilla', compact('participante', 'plantilla', 'capacitacion'));
        return $pdf->stream('vista_previa_diploma.pdf');
    }
}
