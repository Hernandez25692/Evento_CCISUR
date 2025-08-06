<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantilla;
use App\Models\Capacitacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\PlantillaGlobal;

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
            'tipo_certificado' => 'required|in:generico,convenio',
            'titulo_convenio' => 'nullable|string|max:255',
        ]);

        $plantilla = Plantilla::where('capacitacion_id', $capacitacion_id)->first();
        if (!$plantilla) {
            $plantilla = new Plantilla();
            $plantilla->capacitacion_id = $capacitacion_id;
        }

        $plantilla->fecha_emision = $request->fecha_emision;
        $plantilla->orientacion = $request->orientacion;
        $plantilla->tipo_certificado = $request->tipo_certificado;
        $plantilla->titulo_convenio = $request->titulo_convenio;

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
        $plantilla->nombre_firma_1 = $request->input('nombre_firma_1');
        $plantilla->nombre_firma_2 = $request->input('nombre_firma_2');

        $plantilla->save();

        return redirect()->route('capacitaciones.configuracion.plantilla', $capacitacion_id)
            ->with('success', 'Plantilla guardada correctamente.');
    }

    public function vistaPrevia($capacitacion_id)
    {
        $capacitacion = Capacitacion::findOrFail($capacitacion_id);
        $participantes = $capacitacion->participantes;

        if ($participantes->isEmpty()) {
            return back()->with('error', 'No hay participantes en esta capacitación.');
        }

        // Buscar plantilla local
        $plantilla = $capacitacion->plantilla;

        // Si no hay plantilla específica, usar la primera plantilla global como predeterminada
        if (!$plantilla) {
            $plantilla = \App\Models\PlantillaGlobal::latest()->first();
        }

        $pdf = Pdf::loadView('pdf.diplomas', compact('participantes', 'plantilla', 'capacitacion'));
        return $pdf->stream('vista_previa_diploma.pdf');
    }


    public function configuracionPlantilla($id)
    {
        $capacitacion = Capacitacion::with('plantilla')->findOrFail($id);
        $plantillaExistente = $capacitacion->plantilla !== null;

        // Aquí debe ir la carga de plantillas globales
        $plantillas_globales = \App\Models\PlantillaGlobal::all();

        return view('capacitaciones.plantilla', compact(
            'capacitacion',
            'plantillaExistente',
            'plantillas_globales'
        ));
    }



    public function importarDesdePlantillaGlobal(Request $request, $capacitacion_id)
    {
        $request->validate([
            'plantilla_global_id' => 'required|exists:plantillas_globales,id'
        ]);

        $global = PlantillaGlobal::findOrFail($request->plantilla_global_id);

        // Duplicar archivos al storage
        $fondo = Storage::disk('public')->copy($global->fondo, 'fondos/' . basename($global->fondo));
        $firma1 = $global->firma_1 ? Storage::disk('public')->copy($global->firma_1, 'firmas/' . basename($global->firma_1)) : null;
        $firma2 = $global->firma_2 ? Storage::disk('public')->copy($global->firma_2, 'firmas/' . basename($global->firma_2)) : null;

        // Crear nueva plantilla para esta capacitación
        Plantilla::updateOrCreate(
            ['capacitacion_id' => $capacitacion_id],
            [
                'fondo' => $global->fondo,
                'firma_1' => $global->firma_1,
                'firma_2' => $global->firma_2,
                'nombre_firma_1' => $global->nombre_firma_1,
                'nombre_firma_2' => $global->nombre_firma_2,
                'orientacion' => $global->orientacion,
                'tipo_certificado' => $global->tipo_certificado,
                'titulo_convenio' => $global->titulo_convenio,
                'fecha_emision' => $global->fecha_emision,
            ]
        );

        return redirect()->route('capacitaciones.configuracion.plantilla', $capacitacion_id)
            ->with('success', 'Plantilla global aplicada correctamente.');
    }
}
