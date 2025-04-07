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
    public function index(Request $request)
    {
        $query = Capacitacion::withCount('participantes');

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('lugar', 'like', '%' . $request->buscar . '%')
                  ->orWhere('impartido_por', 'like', '%' . $request->buscar . '%');
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo_formacion', $request->tipo);
        }

        if ($request->filled('modalidad')) {
            $query->where('forma', $request->modalidad);
        }

        if ($request->filled('desde')) {
            $query->whereDate('fecha', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('fecha', '<=', $request->hasta);
        }

        $capacitaciones = $query->orderBy('fecha', 'desc')->paginate(12);

        // Obtener tipos únicos para los filtros
        $tipo_formacion = Capacitacion::select('tipo_formacion')
            ->distinct()
            ->whereNotNull('tipo_formacion')
            ->pluck('tipo_formacion');

        return view('capacitaciones.index', compact('capacitaciones', 'tipo_formacion'));
    }

    /**
     * Muestra el formulario para crear una nueva capacitación.
     */
    public function create()
    {
        return view('capacitaciones.create');
    }

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

    public function edit($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        return view('capacitaciones.edit', compact('capacitacion'));
    }

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

    public function destroy($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);

        if ($capacitacion->imagen) {
            Storage::delete('public/' . $capacitacion->imagen);
        }

        $capacitacion->delete();

        return redirect()->route('capacitaciones.index')->with('success', 'Capacitación eliminada correctamente.');
    }

    public function listarParticipantes($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        $participantes = $capacitacion->participantes;
        return view('capacitaciones.participantes', compact('capacitacion', 'participantes'));
    }

    public function agregarPlantilla($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        $plantillaExistente = Plantilla::where('capacitacion_id', $id)->exists();
        return view('capacitaciones.plantilla', compact('capacitacion', 'plantillaExistente'));
    }

    public function guardarPlantilla(Request $request, $id)
    {
        $request->validate([
            'firma' => 'required|image|max:2048',
            'fondo' => 'required|image|max:2048',
            'fecha_emision' => 'required|date',
            'orientacion' => 'required|in:vertical,horizontal',
        ]);

        $capacitacion = Capacitacion::findOrFail($id);

        $plantilla = Plantilla::firstOrNew(['capacitacion_id' => $id]);
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

    public function generarDiplomas($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        $plantilla = Plantilla::where('capacitacion_id', $id)->first();
        $participantes = $capacitacion->participantes;

        if (!$plantilla) {
            return redirect()->route('capacitaciones.index')->with('error', 'No hay plantilla de diploma para esta capacitación.');
        }

        $orientacion = $plantilla->orientacion === 'vertical' ? 'portrait' : 'landscape';

        $pdf = PDF::loadView('pdf.diplomas', compact('capacitacion', 'plantilla', 'participantes'))
            ->setPaper('letter', $orientacion);

        return $pdf->download('diplomas.pdf');
    }

    public function vistaPreviaDiploma($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        $plantilla = Plantilla::where('capacitacion_id', $id)->first();
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
