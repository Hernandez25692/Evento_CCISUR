<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantilla;
use App\Models\Capacitacion;
use Illuminate\Support\Facades\Storage;
use App\Models\PlantillaGlobal;
use App\Services\DiplomaCamposService;
use App\Services\VerificacionDiplomaService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PlantillaDiplomaController extends Controller
{
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
                'fondo_width' => $global->fondo_width,
                'fondo_height' => $global->fondo_height,
                'campos' => $global->campos,
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

        return redirect()->route('capacitaciones.plantilla', $capacitacion_id)
            ->with('success', 'Plantilla global aplicada correctamente.');
    }

    public function editorCampos($capacitacion_id)
    {
        $capacitacion = Capacitacion::with('plantilla')->findOrFail($capacitacion_id);
        $plantilla = $capacitacion->plantilla;

        if (!$plantilla || !$plantilla->fondo) {
            return redirect()->route('capacitaciones.plantilla', $capacitacion_id)
                ->with('error', 'Primero debes guardar una plantilla con imagen de fondo.');
        }

        $participantes = $capacitacion->participantes()
            ->wherePivot('habilitado_diploma', true)
            ->get(['participantes.id', 'participantes.nombre_completo']);

        $contenidos = DiplomaCamposService::contenidoPorDefecto($capacitacion, $plantilla);
        $contenidos['nombre'] = '(agrega participantes habilitados para previsualizar con un nombre real)';

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

        // QR de muestra para el editor: el contenido codificado no cambia
        // el aspecto visual del QR, así que un solo QR (apuntando a la URL
        // real de verificación del primer participante, si hay alguno)
        // sirve para posicionar/dimensionar el campo.
        $urlQrPreview = $participantes->first()
            ? route('diplomas.verificar', VerificacionDiplomaService::codigoPara($capacitacion->id, $participantes->first()->id))
            : route('diplomas.verificar', 'demo');
        $qrPreview = 'data:image/svg+xml;base64,' . base64_encode(QrCode::format('svg')->size(200)->generate($urlQrPreview));

        return view('capacitaciones.plantilla-campos', [
            'capacitacion' => $capacitacion,
            'plantilla' => $plantilla,
            'campos' => DiplomaCamposService::resolve($plantilla->campos),
            'etiquetas' => DiplomaCamposService::ETIQUETAS,
            'fuentes' => DiplomaCamposService::FUENTES,
            'defaults' => DiplomaCamposService::defaults(),
            'contenidos' => $contenidos,
            'firmas' => $firmas,
            'participantes' => $participantes->pluck('nombre_completo', 'id'),
            'participanteInicial' => $participantes->first()->id ?? null,
            'qrPreview' => $qrPreview,
        ]);
    }

    public function guardarCampos(Request $request, $capacitacion_id)
    {
        $request->validate([
            'campos' => 'required|string',
        ]);

        $plantilla = Plantilla::where('capacitacion_id', $capacitacion_id)->firstOrFail();

        $campos = json_decode($request->input('campos'), true) ?? [];
        $plantilla->campos = DiplomaCamposService::sanitize($campos);
        $plantilla->save();

        return redirect()->route('capacitaciones.plantilla.campos', $capacitacion_id)
            ->with('success', '✅ Posiciones guardadas correctamente.');
    }
}
