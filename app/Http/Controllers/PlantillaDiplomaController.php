<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantilla;
use App\Models\Capacitacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PlantillaDiplomaController extends Controller
{
    public function store(Request $request, $capacitacion_id)
    {
        $request->validate([
            'fondo' => 'required|image',
            'firma_1' => 'nullable|image',
            'firma_2' => 'nullable|image',
            'fecha_emision' => 'required|date',
            'orientacion' => 'required|in:horizontal,vertical',
        ]);

        $plantilla = Plantilla::where('capacitacion_id', $capacitacion_id)->first();
        if (!$plantilla) {
            $plantilla = new Plantilla();
            $plantilla->capacitacion_id = $capacitacion_id;
        }

        $plantilla->fecha_emision = $request->fecha_emision;
        $plantilla->orientacion = $request->orientacion;

        // Fondo
        if ($request->hasFile('fondo')) {
            if ($plantilla->fondo) {
                Storage::disk('public')->delete($plantilla->fondo);
            }
            $plantilla->fondo = $request->file('fondo')->store('fondos', 'public');
        }

        // Firma 1
        if ($request->hasFile('firma_1')) {
            if ($plantilla->firma_1) {
                Storage::disk('public')->delete($plantilla->firma_1);
            }
            $plantilla->firma_1 = $request->file('firma_1')->store('firmas', 'public');
        } elseif ($plantilla->firma_1) {
            // Si no se envió firma_1 pero existe, eliminarla
            Storage::disk('public')->delete($plantilla->firma_1);
            $plantilla->firma_1 = null;
        }

        // Firma 2
        if ($request->hasFile('firma_2')) {
            if ($plantilla->firma_2) {
                Storage::disk('public')->delete($plantilla->firma_2);
            }
            $plantilla->firma_2 = $request->file('firma_2')->store('firmas', 'public');
        } elseif ($plantilla->firma_2) {
            // Si no se envió firma_2 pero existe, eliminarla
            Storage::disk('public')->delete($plantilla->firma_2);
            $plantilla->firma_2 = null;
        }

        $plantilla->save();

        return redirect()->route('capacitaciones.configuracion.plantilla', $capacitacion_id)
                         ->with('success', 'Plantilla guardada correctamente.');
    }

    public function vistaPrevia($capacitacion_id)
    {
        $capacitacion = Capacitacion::findOrFail($capacitacion_id);
        $plantilla = $capacitacion->plantilla;
        $participantes = $capacitacion->participantes;

        if ($participantes->isEmpty()) {
            return back()->with('error', 'No hay participantes en esta capacitación.');
        }

        $pdf = Pdf::loadView('pdf.diplomas', compact('participantes', 'plantilla', 'capacitacion'));
        return $pdf->stream('vista_previa_diploma.pdf');
    }

    public function configuracionPlantilla($id)
    {
        $capacitacion = Capacitacion::with('plantilla')->findOrFail($id);
        $plantillaExistente = $capacitacion->plantilla !== null;

        return view('capacitaciones.plantilla', compact('capacitacion', 'plantillaExistente'));
    }
}
