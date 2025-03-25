<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Capacitacion;
use App\Models\Participante;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total de capacitaciones registradas
        $totalCapacitaciones = Capacitacion::count();

        // Total de participantes únicos (sin duplicados)
        $totalParticipantesUnicos = Participante::count();

        // Total de participaciones en capacitaciones (con duplicados)
        $totalParticipaciones = DB::table('capacitacion_participante')->count();

        // Obtener participantes por capacitación
        $participantesPorCapacitacion = Capacitacion::withCount('participantes')->get();

        // Gráfico: etiquetas y datos
        $capacitacionesLabels = $participantesPorCapacitacion->pluck('nombre');
        $participantesData = $participantesPorCapacitacion->pluck('participantes_count');

        // Función adicional: capacitaciones por mes
        $capacitacionesPorMes = Capacitacion::select(
            DB::raw("DATE_FORMAT(fecha, '%Y-%m') as mes"),
            DB::raw("count(*) as total")
        )
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        return view('dashboard.index', compact(
            'totalCapacitaciones',
            'totalParticipantesUnicos',
            'totalParticipaciones',
            'capacitacionesLabels',
            'participantesData',
            'capacitacionesPorMes'
        ));
    }

    public function filtro(Request $request)
    {
        $query = Participante::query();

        if ($request->filled('edad')) {
            $query->where('edad', $request->edad);
        }

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->filled('nivel_educativo')) {
            $query->where('nivel_educativo', $request->nivel_educativo);
        }

        if ($request->filled('empresa')) {
            $query->where('empresa', 'like', "%{$request->empresa}%");
        }

        if ($request->filled('municipio')) {
            $query->where('municipio', 'like', "%{$request->municipio}%");
        }

        $participantes = $query->paginate(10);

        return view('dashboard.filtro', compact('participantes'));
    }
}
