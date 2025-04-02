<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use App\Models\Capacitacion;
use App\Models\Participante;
use App\Models\Plantilla;

class CapacitacionController extends Controller
{
    /**
     * Muestra todas las capacitaciones en la vista principal.
     */
    public function index()
    {
        // Obtener todas las capacitaciones ordenadas por fecha de creación
        $capacitaciones = Capacitacion::orderBy('created_at', 'desc')->get();
        return view('capacitaciones.index', compact('capacitaciones'));
    }

    /**
     * Muestra el formulario para crear una nueva capacitación.
     */
    public function create()
    {
        return view('capacitaciones.create');
    }

    /**
     * Guarda una nueva capacitación en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'lugar' => 'required',
            'fecha' => 'required|date',
            'impartido_por' => 'required',
            'descripcion' => 'nullable',
            'imagen' => 'nullable|image|max:2048',
            'tipo_formacion' => 'nullable|string',
            'duracion' => 'nullable|string',
            'forma' => 'nullable|string',
            'cupos' => 'required|in:limitado,ilimitado',
            'limite_participantes' => 'nullable|integer',
            'medio' => 'required|in:gratis,pago',
            'precio_afiliado' => 'nullable|numeric',
            'isv_afiliado' => 'nullable|numeric',
            'precio_no_afiliado' => 'nullable|numeric',
            'isv_no_afiliado' => 'nullable|numeric',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('capacitaciones', 'public');
        }

        Capacitacion::create($data);

        return redirect()->route('capacitaciones.index')->with('success', 'Capacitación creada correctamente.');
    }




    /**
     * Muestra el formulario de edición de una capacitación.
     */
    public function edit($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        return view('capacitaciones.edit', compact('capacitacion'));
    }

    /**
     * Actualiza los datos de una capacitación.
     */
    public function update(Request $request, $id)
    {
        $capacitacion = Capacitacion::findOrFail($id);

        $request->validate([
            'nombre' => 'required',
            'lugar' => 'required',
            'fecha' => 'required|date',
            'impartido_por' => 'required',
            'descripcion' => 'nullable',
            'imagen' => 'nullable|image|max:2048',
            'tipo_formacion' => 'nullable|string',
            'duracion' => 'nullable|string',
            'forma' => 'nullable|string',
            'cupos' => 'required|in:limitado,ilimitado',
            'limite_participantes' => 'nullable|integer',
            'medio' => 'required|in:gratis,pago',
            'precio_afiliado' => 'nullable|numeric',
            'isv_afiliado' => 'nullable|numeric',
            'precio_no_afiliado' => 'nullable|numeric',
            'isv_no_afiliado' => 'nullable|numeric',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            if ($capacitacion->imagen) {
                Storage::delete('public/' . $capacitacion->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('capacitaciones', 'public');
        }

        $capacitacion->update($data);

        return redirect()->route('capacitaciones.index')->with('success', 'Capacitación actualizada correctamente.');
    }




    /**
     * Elimina una capacitación y sus recursos relacionados.
     */
    public function destroy($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);

        if ($capacitacion->imagen) {
            Storage::delete('public/' . $capacitacion->imagen);
        }

        $capacitacion->delete();

        return redirect()->route('capacitaciones.index')->with('success', 'Capacitación eliminada correctamente.');
    }

    /**
     * Lista los participantes de una capacitación.
     */
    public function listarParticipantes($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        $participantes = $capacitacion->participantes;
        return view('capacitaciones.participantes', compact('capacitacion', 'participantes'));
    }

    /**
     * Muestra el formulario para agregar una plantilla de diploma.
     */
    public function agregarPlantilla($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        $plantillaExistente = Plantilla::where('capacitacion_id', $id)->exists();
        return view('capacitaciones.plantilla', compact('capacitacion', 'plantillaExistente'));
    }

    /**
     * Guarda la plantilla de diploma en la base de datos.
     */
    public function guardarPlantilla(Request $request, $id)
    {
        $request->validate([
            'firma' => 'required|image|max:2048',
            'fondo' => 'required|image|max:2048',
            'fecha_emision' => 'required|date',
            'orientacion' => 'required|in:vertical,horizontal',
        ]);

        $capacitacion = Capacitacion::findOrFail($id);

        $plantilla = Plantilla::where('capacitacion_id', $id)->first();
        if (!$plantilla) {
            $plantilla = new Plantilla();
            $plantilla->capacitacion_id = $id;
        }

        $plantilla->fecha_emision = $request->fecha_emision;
        $plantilla->orientacion = $request->orientacion;

        if ($request->hasFile('firma')) {
            if ($plantilla->firma) {
                Storage::delete('public/' . $plantilla->firma);
            }
            $plantilla->firma = $request->file('firma')->store('plantillas', 'public');
        }

        if ($request->hasFile('fondo')) {
            if ($plantilla->fondo) {
                Storage::delete('public/' . $plantilla->fondo);
            }
            $plantilla->fondo = $request->file('fondo')->store('plantillas', 'public');
        }

        $plantilla->save();

        return redirect()->back()->with('success', '✅ Plantilla guardada correctamente.');
    }


    /**
     * Genera diplomas en PDF para los participantes de una capacitación.
     */
    public function generarDiplomas($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        $plantilla = Plantilla::where('capacitacion_id', $id)->first();
        $participantes = $capacitacion->participantes;

        if (!$plantilla) {
            return redirect()->route('capacitaciones.index')->with('error', 'No hay plantilla de diploma para esta capacitación.');
        }

        // Ajustar tamaño de papel a Carta y orientación dinámica
        $orientacion = $plantilla->orientacion === 'vertical' ? 'portrait' : 'landscape';

        $pdf = PDF::loadView('pdf.diplomas', compact('capacitacion', 'plantilla', 'participantes'))
            ->setPaper('letter', $orientacion); // Ajustar a tamaño carta

        return $pdf->download('diplomas.pdf');
    }

    /**
     * Muestra una vista previa de un diploma en PDF.
     */
    public function vistaPreviaDiploma($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        $plantilla = Plantilla::where('capacitacion_id', $id)->first();
        $capacitacion = Capacitacion::findOrFail($id);
        $participante = $capacitacion->participantes()->first();

        if (!$plantilla || !$participante) {
            return redirect()->back()->with('error', 'Debe existir una plantilla y al menos un participante para la vista previa.');
        }

        $orientacion = $plantilla->orientacion === 'vertical' ? 'portrait' : 'landscape';

        $pdf = PDF::loadView('pdf.diplomas', [
            'capacitacion' => $capacitacion,
            'plantilla' => $plantilla,
            'participantes' => collect([$participante])
        ])->setPaper('letter', $orientacion);

        return $pdf->stream('vista_previa.pdf');
    }
}
