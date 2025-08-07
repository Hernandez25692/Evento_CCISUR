<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use App\Models\Capacitacion;
use App\Models\Participante;
use App\Models\Plantilla;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Str;
use App\Models\PlantillaGlobal;
use Spatie\Browsershot\Browsershot;
use ZipArchive;

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
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i',

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

        // Mostrar qué datos llegan y si cambian
        foreach ($request->except(['_token', '_method']) as $key => $valorNuevo) {
            $valorActual = $capacitacion->$key ?? 'NO EXISTE';
            if ($valorActual != $valorNuevo) {
                \Log::info("CAMBIO detectado en '$key': De '$valorActual' a '$valorNuevo'");
                $capacitacion->$key = $valorNuevo;
            } else {
                \Log::info("SIN CAMBIO en '$key': sigue como '$valorActual'");
            }
        }

        // Imagen
        if ($request->hasFile('imagen')) {
            if ($capacitacion->imagen) {
                Storage::delete('public/' . $capacitacion->imagen);
            }
            $capacitacion->imagen = $request->file('imagen')->store('capacitaciones', 'public');
            \Log::info("Imagen actualizada: " . $capacitacion->imagen);
        }

        $resultado = $capacitacion->save();

        \Log::info("Resultado del guardado: " . ($resultado ? 'ÉXITO' : 'FALLÓ'));

        return redirect()->route('capacitaciones.index')->with('success', '✅ Formación actualizada con éxito');
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
        $capacitacion = Capacitacion::with(['participantes' => function ($query) {
            $query->withPivot('habilitado_diploma');
        }])->findOrFail($id);

        $participantes = $capacitacion->participantes;

        return view('capacitaciones.participantes', compact('capacitacion', 'participantes'));
    }


    public function agregarPlantilla($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        $plantillaExistente = Plantilla::where('capacitacion_id', $id)->exists();

        // NUEVO: Obtener todas las plantillas globales
        $plantillas_globales = PlantillaGlobal::all();

        return view('capacitaciones.plantilla', compact(
            'capacitacion',
            'plantillaExistente',
            'plantillas_globales'
        ));
    }


    public function guardarPlantilla(Request $request, $id)
    {
        $request->validate([
            'firma_1' => 'nullable|image|max:2048',
            'firma_2' => 'nullable|image|max:2048',
            'fondo' => 'nullable|image|max:2048',
            'fecha_emision' => 'required|date',
            'orientacion' => 'required|in:vertical,horizontal',
            'nombre_firma_1' => 'nullable|string|max:255',
            'nombre_firma_2' => 'nullable|string|max:255',
            'tipo_certificado' => 'required|in:generico,convenio',
            'titulo_convenio' => 'nullable|string|max:255',
        ]);

        $capacitacion = Capacitacion::findOrFail($id);
        $plantilla = Plantilla::firstOrNew(['capacitacion_id' => $id]);

        $plantilla->fecha_emision = $request->fecha_emision;
        $plantilla->orientacion = $request->orientacion;
        $plantilla->tipo_certificado = $request->input('tipo_certificado');
        $plantilla->titulo_convenio = $request->input('titulo_convenio');

        $plantilla->nombre_firma_1 = $request->input('nombre_firma_1');
        $plantilla->nombre_firma_2 = $request->input('nombre_firma_2');

        // Firma 1
        if ($request->hasFile('firma_1')) {
            if ($plantilla->firma_1) {
                Storage::delete('public/' . $plantilla->firma_1);
            }
            $plantilla->firma_1 = $request->file('firma_1')->store('plantillas', 'public');
        }

        // Firma 2
        if ($request->hasFile('firma_2')) {
            if ($plantilla->firma_2) {
                Storage::delete('public/' . $plantilla->firma_2);
            }
            $plantilla->firma_2 = $request->file('firma_2')->store('plantillas', 'public');
        }

        // Fondo
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
        $plantilla = $capacitacion->plantilla;
        $participantes = $capacitacion->participantes()
            ->wherePivot('habilitado_diploma', true)
            ->get();

        $orientacion = $plantilla->orientacion === 'vertical' ? 'portrait' : 'landscape';

        $pdf = PDF::loadView('pdf.diplomas', [
            'capacitacion' => $capacitacion,
            'plantilla' => $plantilla,
            'participantes' => $participantes
        ])->setPaper('letter', $orientacion);

        // Nombre del archivo: ejemplo "Diplomas_Curso Laravel_2025-05-30.pdf"
        $nombreArchivo = 'Diplomas_' . Str::slug($capacitacion->nombre) . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($nombreArchivo);
    }




    public function vistaPreviaDiploma($id)
    {
        $capacitacion = Capacitacion::findOrFail($id);
        $plantilla = Plantilla::where('capacitacion_id', $id)->first();
        $participante = $capacitacion->participantes()
            ->wherePivot('habilitado_diploma', true)
            ->first();


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


    public function descargarDiplomasImagenes($id)
    {
        $capacitacion = Capacitacion::with('plantilla', 'participantes')->findOrFail($id);
        $plantilla = $capacitacion->plantilla;
        $participantes = $capacitacion->participantes()->wherePivot('habilitado_diploma', true)->get();

        if (!$plantilla || $participantes->isEmpty()) {
            return back()->with('error', 'Debe existir una plantilla y al menos un participante.');
        }

        $folderName = Str::slug($capacitacion->nombre);
        $outputDir = storage_path("app/diplomas-imagenes/{$folderName}");

        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        foreach ($participantes as $participante) {
            // Generar PDF temporal
            $html = view('pdf.diplomas', [
                'capacitacion' => $capacitacion,
                'plantilla' => $plantilla,
                'participantes' => collect([$participante])
            ])->render();

            $pdfPath = "{$outputDir}/" . Str::slug($participante->nombre_completo) . ".pdf";
            $pngPath = "{$outputDir}/" . Str::slug($participante->nombre_completo) . ".png";

            PDF::loadHTML($html)->setPaper('letter', $plantilla->orientacion === 'vertical' ? 'portrait' : 'landscape')->save($pdfPath);

            // Convertir PDF a PNG con Imagick
            $imagick = new \Imagick();
            $imagick->setResolution(300, 300); // Alta calidad
            $imagick->readImage($pdfPath);
            $imagick->setImageFormat('png');
            $imagick->writeImage($pngPath);
            $imagick->clear();
            $imagick->destroy();

            // Opcional: borrar PDF temporal
            unlink($pdfPath);
        }

        // Crear ZIP
        $zipName = "Diplomas_{$folderName}.zip";
        $zipPath = storage_path("app/{$zipName}");

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach (glob("{$outputDir}/*.png") as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
