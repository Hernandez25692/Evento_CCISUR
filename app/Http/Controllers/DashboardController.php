<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Capacitacion;
use App\Models\Participante;

class DashboardController extends Controller
{
    public function index()
    {
        // Contar el total de capacitaciones y participantes
        $totalCapacitaciones = Capacitacion::count();
        $totalParticipantes = Participante::count();

        // Obtener cantidad de participantes por capacitación
        $participantesPorCapacitacion = Capacitacion::withCount('participantes')->get();

        // Convertir los datos a un formato JSON para los gráficos
        $capacitacionesLabels = $participantesPorCapacitacion->pluck('nombre');
        $participantesData = $participantesPorCapacitacion->pluck('participantes_count');

        return view('dashboard.index', compact(
            'totalCapacitaciones',
            'totalParticipantes',
            'capacitacionesLabels',
            'participantesData'
        ));
    }
    //Dashboard de Filtros
    public function filtro(Request $request)
    {
        // Obtiene los valores de los filtros desde el formulario
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
            $query->where('empresa', 'LIKE', '%' . $request->empresa . '%');
        }

        if ($request->filled('municipio')) {
            $query->where('municipio', 'LIKE', '%' . $request->municipio . '%');
        }

        // Obtener los participantes filtrados
        $participantes = $query->get();

        return view('dashboard.filtro', compact('participantes'));
    }
}
