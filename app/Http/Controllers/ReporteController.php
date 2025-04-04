<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Capacitacion;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteCapacitacionesExport;
use App\Exports\ReporteCapacitacionesDetalleExport;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $capacitaciones = null;

        if ($request->filled(['desde', 'hasta'])) {
            $desde = $request->input('desde');
            $hasta = $request->input('hasta');

            $capacitaciones = Capacitacion::withCount('participantes')
                ->whereDate('fecha', '>=', $desde)
                ->whereDate('fecha', '<=', $hasta)
                ->orderBy('fecha', 'asc')
                ->get();
        }

        return view('reportes.capacitaciones', compact('capacitaciones'));
    }

    public function exportarExcel(Request $request)
{
    $desde = $request->input('desde');
    $hasta = $request->input('hasta');
    $tipo = $request->input('tipo', 'resumen');

    if ($tipo === 'detalle') {
        return Excel::download(
            new ReporteCapacitacionesDetalleExport($desde, $hasta),
            'Detalle_Capacitaciones.xlsx'
        );
    }

    return Excel::download(
        new ReporteCapacitacionesExport($desde, $hasta),
        'Resumen_Capacitaciones.xlsx'
    );
}
}
