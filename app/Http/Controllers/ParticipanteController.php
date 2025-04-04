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

        // Buscar participante por identidad
        $participante = Participante::where('identidad', $request->identidad)->first();

        // Si ya existe y está vinculado a esta capacitación, mostrar advertencia
        if ($participante && $participante->capacitaciones->contains($capacitacion->id)) {
            return redirect()->back()->with('warning', '⚠️ Este participante ya está registrado en esta capacitación.');
        }

        // Crear o actualizar datos del participante
        if (!$participante) {
            $participante = new Participante();
        }

        $participante->fill($request->except('comprobante', 'afiliado'));
        $participante->afiliado = $request->boolean('afiliado');

        if (strtolower($capacitacion->medio) === 'pago') {
            $esAfiliado = $participante->afiliado;
            $precio = $esAfiliado ? $capacitacion->precio_afiliado : $capacitacion->precio_no_afiliado;
            $isv = $esAfiliado ? $capacitacion->isv_afiliado : $capacitacion->isv_no_afiliado;
            $total = $precio + $isv;

            $participante->precio = $precio;
            $participante->isv = $isv;
            $participante->total = $total;

            if ($request->hasFile('comprobante')) {
                if ($participante->comprobante) {
                    Storage::delete('public/' . $participante->comprobante);
                }
                $participante->comprobante = $request->file('comprobante')->store('comprobantes', 'public');
            }
        }

        $participante->save();

        // Asociar sin duplicar
        $participante->capacitaciones()->syncWithoutDetaching([$capacitacion->id]);

        return redirect()->route('capacitaciones.participantes.create', $capacitacion->id)
            ->with('success', '✅ Participante agregado correctamente.');
    }

    public function destroy($id)
    {
        $participante = Participante::findOrFail($id);
        $capacitacion_id = $participante->capacitaciones->first()->id ?? null;

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

    public function edit($capacitacion_id, $participante_id)
    {
        $capacitacion = Capacitacion::findOrFail($capacitacion_id);
        $participante = Participante::findOrFail($participante_id);

        return view('participantes.edit', compact('capacitacion', 'participante'));
    }

    public function update(Request $request, $capacitacion_id, $participante_id)
    {
        $capacitacion = Capacitacion::findOrFail($capacitacion_id);
        $participante = Participante::findOrFail($participante_id);

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

        // Verificar si la identidad ya está usada por otro participante
        $otro = Participante::where('identidad', $request->identidad)
            ->where('id', '!=', $participante_id)
            ->first();

        if ($otro) {
            return redirect()->back()->with('warning', '⚠️ Ya existe otro participante con esta identidad.');
        }

        $participante->fill($request->except('comprobante', 'afiliado'));
        $participante->afiliado = $request->boolean('afiliado');

        if (strtolower($capacitacion->medio) === 'pago') {
            $precio = $participante->afiliado ? $capacitacion->precio_afiliado : $capacitacion->precio_no_afiliado;
            $isv = $participante->afiliado ? $capacitacion->isv_afiliado : $capacitacion->isv_no_afiliado;
            $total = $precio + $isv;

            $participante->precio = $precio;
            $participante->isv = $isv;
            $participante->total = $total;

            if ($request->hasFile('comprobante')) {
                if ($participante->comprobante) {
                    Storage::delete('public/' . $participante->comprobante);
                }
                $participante->comprobante = $request->file('comprobante')->store('comprobantes', 'public');
            }
        }

        $participante->save();

        return redirect()->route('capacitaciones.participantes', $capacitacion_id)
            ->with('success', '✅ Participante actualizado correctamente.');
    }
}
