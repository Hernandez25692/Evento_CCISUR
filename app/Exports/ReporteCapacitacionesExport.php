<?php

namespace App\Exports;

use App\Models\Capacitacion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class ReporteCapacitacionesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $desde;
    protected $hasta;

    public function __construct($desde, $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    public function collection()
    {
        $capacitaciones = Capacitacion::with('participantes')
            ->whereDate('fecha', '>=', $this->desde)
            ->whereDate('fecha', '<=', $this->hasta)
            ->get();

        $reporte = new Collection();

        foreach ($capacitaciones as $cap) {
            $participantes = $cap->participantes;

            $empresas = $participantes->whereNotNull('empresa')->pluck('empresa')->unique()->count();
            $hombres = $participantes->where('genero', 'Masculino')->count();
            $mujeres = $participantes->where('genero', 'Femenino')->count();
            $total = $participantes->count();

            $edades = [
                '-18' => 0,
                '18-21' => 0,
                '22-30' => 0,
                '31-40' => 0,
                '41-50' => 0,
                '51-60' => 0,
                '61+' => 0,
            ];

            foreach ($participantes as $p) {
                $edad = $p->edad;
                if ($edad < 18) $edades['-18']++;
                elseif ($edad <= 21) $edades['18-21']++;
                elseif ($edad <= 30) $edades['22-30']++;
                elseif ($edad <= 40) $edades['31-40']++;
                elseif ($edad <= 50) $edades['41-50']++;
                elseif ($edad <= 60) $edades['51-60']++;
                else $edades['61+']++;
            }

            $reporte->push([
                $cap->nombre,
                $cap->forma ?? 'N/A',
                $cap->tipo_formacion ?? 'N/A',
                $cap->fecha ? \Carbon\Carbon::parse($cap->fecha)->format('d/m/Y') : 'N/A',
                $empresas,
                $hombres,
                $mujeres,
                $total,
                $edades['-18'],
                $edades['18-21'],
                $edades['22-30'],
                $edades['31-40'],
                $edades['41-50'],
                $edades['51-60'],
                $edades['61+'],
            ]);
        }

        return $reporte;
    }

    public function headings(): array
    {
        return [
            'Nombre del evento',
            'Modalidad',
            'Tipo de evento',
            'Fecha',
            'Empresas',
            'Hombres',
            'Mujeres',
            'Total participantes',
            '-18',
            '18 A 21',
            '22 A 30',
            '31 A 40',
            '41 A 50',
            '51 A 60',
            '61 EN ADELANTE',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastColumn = 'O'; // 15 columnas
                $lastRow = $sheet->getHighestRow();

                // Encabezado: estilo azul con letra blanca
                $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F81BD'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // Bordes generales
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // Alto de encabezado
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Activar autofiltro para "tabla dinámica manual"
                $sheet->setAutoFilter("A1:{$lastColumn}1");
            },
        ];
    }
}
