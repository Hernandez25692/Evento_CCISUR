<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participante;
use App\Models\Capacitacion;
use App\Exports\ParticipantesExport;
use App\Imports\ParticipantesImport;
use Maatwebsite\Excel\Facades\Excel;

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
        $capacitacion_id = $participante->capacitaciones->first()->id ?? null;
        $participante->capacitaciones()->detach();
        $participante->delete();

        return redirect()->route('capacitaciones.participantes', $capacitacion_id)
                         ->with('success', 'Participante eliminado correctamente.');
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'nombre_completo' => 'required',
            'correo' => 'required|email',
            'telefono' => 'required',
            'empresa' => 'nullable',
            'puesto' => 'nullable',
            'edad' => 'required|integer',
            'identidad' => 'required',
            'nivel_educativo' => 'required',
            'genero' => 'required',
            'municipio' => 'required',
            'ciudad' => 'required',
        ]);

        $participante = Participante::firstOrCreate(
            ['identidad' => $request->identidad],
            $request->except('identidad')
        );

        $participante->capacitaciones()->syncWithoutDetaching([$id]);

        return redirect()->route('capacitaciones.participantes.create', $id)
                         ->with('success', 'Participante agregado correctamente.');
    }

    public function importarExcel(Request $request, $capacitacion_id)
    {
        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new ParticipantesImport($capacitacion_id), $request->file('archivo_excel'));

        return back()->with('success', 'Participantes importados correctamente.');
    }

    public function exportarExcel($capacitacion_id)
    {
        $capacitacion = Capacitacion::findOrFail($capacitacion_id);
        $nombre = 'Participantes_' . str_replace(' ', '_', $capacitacion->nombre) . '.xlsx';

        return Excel::download(new ParticipantesExport($capacitacion), $nombre);
    }
}
