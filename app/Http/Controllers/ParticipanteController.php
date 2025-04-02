<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participante;
use App\Models\Capacitacion;
use App\Exports\ParticipantesExport;
use App\Imports\ParticipantesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ParticipanteController extends Controller
{
    public function create($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        return view('participantes.create', compact('capacitacion'));
    }

    public function store(Request $request, $id)
    {
        $capacitacion = Capacitacion::findOrFail($id);

        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'empresa' => 'nullable|string|max:255',
            'puesto' => 'nullable|string|max:255',
            'edad' => 'required|integer|min:1|max:120',
            'identidad' => 'required|string|max:50',
            'nivel_educativo' => 'required|string|max:50',
            'genero' => 'required|string|max:20',
            'municipio' => 'required|string|max:100',
            'ciudad' => 'required|string|max:100',
            'afiliado' => 'nullable|boolean',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Buscar o crear por identidad
        $participante = Participante::firstOrNew(['identidad' => $request->identidad]);

        $participante->fill($request->except('comprobante', 'afiliado'));
        $participante->afiliado = $request->boolean('afiliado'); // Devuelve true o false como 1 o 0

        // Evaluar si la capacitación es de pago
        if (strtolower($capacitacion->medio) === 'pago') {
            $esAfiliado = $participante->afiliado;
            $precio = $esAfiliado ? $capacitacion->precio_afiliado : $capacitacion->precio_no_afiliado;
            $isv = $esAfiliado ? $capacitacion->isv_afiliado : $capacitacion->isv_no_afiliado;
            $total = $precio + $isv;

            $participante->precio = $precio;
            $participante->isv = $isv;
            $participante->total = $total;

            if ($request->hasFile('comprobante')) {
                // Si ya existe comprobante anterior, lo borra
                if ($participante->comprobante) {
                    Storage::delete('public/' . $participante->comprobante);
                }

                $participante->comprobante = $request->file('comprobante')->store('comprobantes', 'public');
            }
        }

        $participante->save();

        // Vincular sin duplicar
        $participante->capacitaciones()->syncWithoutDetaching([$capacitacion->id]);

        return redirect()->route('capacitaciones.participantes.create', $capacitacion->id)
            ->with('success', '✅ Participante agregado correctamente.');
    }


    public function destroy($id)
    {
        $participante = Participante::findOrFail($id);
        $capacitacion_id = $participante->capacitaciones->first()->id ?? null;

        // Borrar comprobante si existe
        if ($participante->comprobante) {
            Storage::delete('public/' . $participante->comprobante);
        }

        $participante->capacitaciones()->detach();
        $participante->delete();

        return redirect()->route('capacitaciones.participantes', $capacitacion_id)
            ->with('success', 'Participante eliminado correctamente.');
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
