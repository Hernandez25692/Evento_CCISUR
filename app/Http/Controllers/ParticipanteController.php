<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participante;
use App\Models\Capacitacion;

class ParticipanteController extends Controller
{
    public function create($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        return view('participantes.create', compact('capacitacion'));
    }

    public function destroy($id)
    {
        $participante = Participante::findOrFail($id);
        $capacitacion_id = $participante->capacitacion_id;
        $participante->delete();

        return redirect()->route('capacitaciones.participantes', $capacitacion_id)
                         ->with('success', 'Participante eliminado correctamente.');
    }

    public function store(Request $request, $id)
{
    $request->validate([
        'nombre_completo' => 'required',
        'correo' => 'required|email|unique:participantes',
        'telefono' => 'required',
        'empresa' => 'nullable',
        'puesto' => 'nullable',
        'edad' => 'required|integer',
        'identidad' => 'required|unique:participantes',
        'nivel_educativo' => 'required',
        'genero' => 'required',
        'municipio' => 'required',
        'ciudad' => 'required',
    ]);

    Participante::create(array_merge($request->all(), ['capacitacion_id' => $id]));

    return redirect()->route('capacitaciones.participantes.create', $id)
                     ->with('success', 'Participante agregado correctamente.');
}

}
