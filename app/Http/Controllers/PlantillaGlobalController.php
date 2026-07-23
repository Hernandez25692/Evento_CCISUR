<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlantillaGlobal;
use App\Services\DiplomaCamposService;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        [$ancho, $alto] = getimagesize($request->file('fondo')->getRealPath()) ?: [null, null];
        $data['fondo_width'] = $ancho;
        $data['fondo_height'] = $alto;

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

            [$ancho, $alto] = getimagesize($request->file('fondo')->getRealPath()) ?: [null, null];
            $plantilla->fondo_width = $ancho;
            $plantilla->fondo_height = $alto;
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
            'fondo_width' => $plantilla->fondo_width,
            'fondo_height' => $plantilla->fondo_height,
            'campos' => $plantilla->campos,
            'firma_1' => $plantilla->firma_1 ? asset('storage/' . $plantilla->firma_1) : null,
            'firma_2' => $plantilla->firma_2 ? asset('storage/' . $plantilla->firma_2) : null,
        ]);
    }

    public function editorCampos($id)
    {
        $plantilla = PlantillaGlobal::findOrFail($id);

        // Una plantilla global no está ligada a una capacitación/participantes
        // reales, así que la vista previa usa una capacitación de ejemplo
        // (mismo generador de textos que usa el editor por capacitación y el
        // PDF final, para que los tres queden sincronizados).
        $capacitacionEjemplo = (object) [
            'tipo_formacion' => 'la capacitación',
            'nombre' => 'Nombre de la Capacitación',
            'modalidad' => 'virtual',
            'duracion' => '20',
            'lugar' => 'Comayagua',
            'impartido_por' => 'Nombre del Facilitador',
        ];

        $contenidos = DiplomaCamposService::contenidoPorDefecto($capacitacionEjemplo, $plantilla);
        $contenidos['nombre'] = 'Nombre del Participante';

        $firmas = [
            'firma_1' => [
                'url' => $plantilla->firma_1 ? asset('storage/' . $plantilla->firma_1) : null,
                'nombre' => $plantilla->nombre_firma_1,
            ],
            'firma_2' => [
                'url' => $plantilla->firma_2 ? asset('storage/' . $plantilla->firma_2) : null,
                'nombre' => $plantilla->nombre_firma_2,
            ],
        ];

        // Una plantilla global nunca está ligada a un diploma real, así que
        // el QR de previsualización solo sirve para posicionar/dimensionar
        // el campo (nunca se escanea de verdad).
        $qrPreview = 'data:image/svg+xml;base64,'
            . base64_encode(QrCode::format('svg')->size(200)->generate(route('diplomas.verificar', 'demo')));

        return view('plantillas_globales.campos', [
            'plantilla' => $plantilla,
            'campos' => DiplomaCamposService::resolve($plantilla->campos),
            'etiquetas' => DiplomaCamposService::ETIQUETAS,
            'fuentes' => DiplomaCamposService::FUENTES,
            'defaults' => DiplomaCamposService::defaults(),
            'contenidos' => $contenidos,
            'firmas' => $firmas,
            'participantes' => collect(),
            'participanteInicial' => null,
            'qrPreview' => $qrPreview,
        ]);
    }

    public function guardarCampos(Request $request, $id)
    {
        $request->validate([
            'campos' => 'required|string',
        ]);

        $plantilla = PlantillaGlobal::findOrFail($id);

        $campos = json_decode($request->input('campos'), true) ?? [];
        $plantilla->campos = DiplomaCamposService::sanitize($campos);
        $plantilla->save();

        return redirect()->route('plantillas-globales.campos', $id)
            ->with('success', '✅ Posiciones guardadas correctamente.');
    }
}
