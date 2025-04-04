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

        // Gráfico: capacitaciones por mes
        $capacitacionesPorMes = Capacitacion::select(
            DB::raw("DATE_FORMAT(fecha, '%Y-%m') as mes"),
            DB::raw("count(*) as total")
        )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Gráfico: distribución por género
        $generoData = [
            Participante::where('genero', 'Masculino')->count(),
            Participante::where('genero', 'Femenino')->count(),
            Participante::where('genero', 'Otro')->count()
        ];

        // Distribución por edades
        $rangosEdad = [
            '-18' => 0,
            '18-21' => 0,
            '22-30' => 0,
            '31-40' => 0,
            '41-50' => 0,
            '51-60' => 0,
            '61+' => 0,
        ];

        foreach (Participante::all() as $p) {
            if ($p->edad < 18) $rangosEdad['-18']++;
            elseif ($p->edad <= 21) $rangosEdad['18-21']++;
            elseif ($p->edad <= 30) $rangosEdad['22-30']++;
            elseif ($p->edad <= 40) $rangosEdad['31-40']++;
            elseif ($p->edad <= 50) $rangosEdad['41-50']++;
            elseif ($p->edad <= 60) $rangosEdad['51-60']++;
            else $rangosEdad['61+']++;
        }

        // Total dinero recaudado de capacitaciones de pago
        $totalDineroRecaudado = Capacitacion::where('medio', 'pago')
        ->with('participantes')
        ->get()
        ->flatMap(function ($cap) {
            return $cap->participantes;
        })
        ->sum('total');
    

        // Recaudación por capacitación de pago (solo totales)
        $formacionesPagas = Capacitacion::where('medio', 'pago')->get();

        $recaudacionLabels = [];
        $recaudacionTotales = [];

        foreach ($formacionesPagas as $formacion) {
            $total = $formacion->participantes->sum('total');
            if ($total > 0) {
                $recaudacionLabels[] = $formacion->nombre;
                $recaudacionTotales[] = $total;
            }
        }


        return view('dashboard.index', compact(
            'totalCapacitaciones',
            'totalParticipantesUnicos',
            'totalParticipaciones',
            'capacitacionesLabels',
            'participantesData',
            'capacitacionesPorMes',
            'generoData',
            'rangosEdad',
            'totalDineroRecaudado',
            'recaudacionLabels',
            'recaudacionTotales'
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
