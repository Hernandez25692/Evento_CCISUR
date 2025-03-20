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
}
