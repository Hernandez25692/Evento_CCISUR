<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use App\Models\Capacitacion;
use App\Models\DescargaDiplomaLog;
use Illuminate\Support\Facades\Storage;
use App\Services\DiplomaCamposService;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificadoController extends Controller
{
    public function buscar()
    {
        return view('certificados.buscar');
    }

    public function validarQR()
    {
        return view('validar_qr');
    }

    /**
     * Muestra el formulario para buscar un participante por su identidad.
     
     */
    public function resultado(Request $request)
    {
        $request->validate([
            'identidad' => 'required|string|max:50'
        ]);

        $participante = Participante::where('identidad', $request->identidad)
            ->with(['capacitaciones' => function ($q) {
                $q->withPivot('habilitado_diploma');
            }])
            ->first();

        return view('certificados.resultado', compact('participante'));
    }

    public function descargar(Request $request, $capacitacion_id, $identidad)
    {
        $capacitacion = Capacitacion::with('plantilla')->findOrFail($capacitacion_id);

        // El admin debe haber publicado explícitamente los diplomas de esta
        // capacitación (ver CapacitacionController::publicarDiplomas). Sin
        // esto, ni el botón aparece en la búsqueda pública ni esta ruta
        // entrega el PDF, aunque alguien adivine la URL.
        if (!$capacitacion->diplomas_publicados) {
            return redirect()->route('certificados.buscar')->with('error', '❌ Los diplomas de esta capacitación aún no han sido publicados por el organizador.');
        }

        // Se busca por identidad (DNI), no por el id interno de la fila, para
        // que la descarga solo sea accesible a quien conoce ese dato personal
        // en vez de poder enumerarse recorriendo IDs consecutivos. Además se
        // exige que el participante esté vinculado a la capacitación y que el
        // diploma esté habilitado (mismo criterio que usa la vista pública
        // para mostrar el botón de descarga).
        $participante = Participante::where('identidad', $identidad)
            ->whereHas('capacitaciones', function ($query) use ($capacitacion_id) {
                $query->where('capacitacion_id', $capacitacion_id)
                    ->where('habilitado_diploma', true);
            })->first();

        if (!$participante) {
            return redirect()->route('certificados.buscar')->with('error', '❌ Participante no encontrado o diploma no habilitado para esta capacitación.');
        }

        $plantilla = $capacitacion->plantilla;

        if (!$plantilla || !$plantilla->fondo) {
            return redirect()->route('certificados.buscar')->with('error', '❌ Esta capacitación no tiene plantilla de diploma configurada.');
        }

        DescargaDiplomaLog::create([
            'capacitacion_id' => $capacitacion->id,
            'participante_id' => $participante->id,
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        // El diploma en PDF es determinista a partir de los datos actuales
        // (participante, capacitación, plantilla), así que se cachea en disco
        // privado usando esos tres timestamps como llave: cualquier cambio en
        // alguno invalida automáticamente la caché sin tocar código en otros
        // controladores. Esto evita repetir el renderizado (el paso más caro
        // de la descarga) cuando muchas personas descargan el mismo diploma
        // en el mismo rango de tiempo, algo frecuente justo tras un evento.
        $cacheKey = sha1(implode('|', [
            $capacitacion->id,
            $participante->id,
            $capacitacion->updated_at,
            $plantilla->updated_at,
            $participante->updated_at,
        ]));
        $cachePath = "diplomas-cache/{$cacheKey}.pdf";

        if (!Storage::disk('local')->exists($cachePath)) {
            $papel = DiplomaCamposService::paperSize($plantilla->fondo_width, $plantilla->fondo_height);
            $orientacion = $papel['orientation'] ?? ($plantilla->orientacion == 'vertical' ? 'portrait' : 'landscape');

            // Usamos la misma vista que en la vista previa, pero con un solo participante
            $pdf = Pdf::loadView('pdf.diplomas', ['participantes' => collect([$participante]), 'plantilla' => $plantilla, 'capacitacion' => $capacitacion])
                ->setPaper($papel['size'], $orientacion);

            Storage::disk('local')->put($cachePath, $pdf->output());
        }

        return response()->download(
            Storage::disk('local')->path($cachePath),
            "Diploma_{$participante->identidad}.pdf"
        );
    }
}
