<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlantillaGlobal;
use Illuminate\Support\Facades\Storage;

class PlantillaGlobalController extends Controller
{
    public function index()
    {
        $plantillas = PlantillaGlobal::latest()->get();
        return view('plantillas_globales.index', compact('plantillas'));
    }

    public function create()
    {
        return view('plantillas_globales.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fondo' => 'required|image',
            'firma_1' => 'nullable|image',
            'firma_2' => 'nullable|image',
            'fecha_emision' => 'required|date',
            'orientacion' => 'required|in:horizontal,vertical',
            'tipo_certificado' => 'required|in:generico,convenio',
            'titulo_convenio' => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'nombre',
            'nombre_firma_1',
            'nombre_firma_2',
            'orientacion',
            'tipo_certificado',
            'titulo_convenio',
            'fecha_emision'
        ]);

        $data['fondo'] = $request->file('fondo')->store('fondos', 'public');
        if ($request->hasFile('firma_1')) {
            $data['firma_1'] = $request->file('firma_1')->store('firmas', 'public');
        }
        if ($request->hasFile('firma_2')) {
            $data['firma_2'] = $request->file('firma_2')->store('firmas', 'public');
        }

        PlantillaGlobal::create($data);

        return redirect()->route('plantillas-globales.index')->with('success', 'Plantilla creada exitosamente.');
    }

    public function edit($id)
    {
        $plantilla = PlantillaGlobal::findOrFail($id);
        return view('plantillas_globales.edit', compact('plantilla'));
    }

    public function update(Request $request, $id)
    {
        $plantilla = PlantillaGlobal::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'fondo' => 'nullable|image',
            'firma_1' => 'nullable|image',
            'firma_2' => 'nullable|image',
            'fecha_emision' => 'required|date',
            'orientacion' => 'required|in:horizontal,vertical',
            'tipo_certificado' => 'required|in:generico,convenio',
            'titulo_convenio' => 'nullable|string|max:255',
        ]);

        $plantilla->update($request->only([
            'nombre',
            'nombre_firma_1',
            'nombre_firma_2',
            'orientacion',
            'tipo_certificado',
            'titulo_convenio',
            'fecha_emision'
        ]));

        if ($request->hasFile('fondo')) {
            Storage::disk('public')->delete($plantilla->fondo);
            $plantilla->fondo = $request->file('fondo')->store('fondos', 'public');
        }

        if ($request->hasFile('firma_1')) {
            Storage::disk('public')->delete($plantilla->firma_1);
            $plantilla->firma_1 = $request->file('firma_1')->store('firmas', 'public');
        }

        if ($request->hasFile('firma_2')) {
            Storage::disk('public')->delete($plantilla->firma_2);
            $plantilla->firma_2 = $request->file('firma_2')->store('firmas', 'public');
        }

        $plantilla->save();

        return redirect()->route('plantillas-globales.index')->with('success', 'Plantilla actualizada correctamente.');
    }

    public function destroy($id)
    {
        $plantilla = PlantillaGlobal::findOrFail($id);
        Storage::disk('public')->delete([
            $plantilla->fondo,
            $plantilla->firma_1,
            $plantilla->firma_2,
        ]);
        $plantilla->delete();

        return redirect()->route('plantillas-globales.index')->with('success', 'Plantilla eliminada correctamente.');
    }

    public function datos($id)
    {
        $plantilla = \App\Models\PlantillaGlobal::findOrFail($id);

        return response()->json([
            'nombre_firma_1' => $plantilla->nombre_firma_1,
            'nombre_firma_2' => $plantilla->nombre_firma_2,
            'titulo_convenio' => $plantilla->titulo_convenio,
            'tipo_certificado' => $plantilla->tipo_certificado,
            'fecha_emision' => $plantilla->fecha_emision,
            'orientacion' => $plantilla->orientacion,
            'fondo' => asset('storage/' . $plantilla->fondo),
            'firma_1' => $plantilla->firma_1 ? asset('storage/' . $plantilla->firma_1) : null,
            'firma_2' => $plantilla->firma_2 ? asset('storage/' . $plantilla->firma_2) : null,
        ]);
    }
}
