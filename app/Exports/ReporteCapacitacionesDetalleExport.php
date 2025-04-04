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

class ReporteCapacitacionesDetalleExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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
        $capacitaciones = Capacitacion::withCount('participantes')
            ->whereDate('fecha', '>=', $this->desde)
            ->whereDate('fecha', '<=', $this->hasta)
            ->orderBy('fecha')
            ->get();

        $detalle = new Collection();

        foreach ($capacitaciones as $c) {
            $detalle->push([
                $c->nombre,
                $c->fecha ? \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') : 'N/A',
                $c->impartido_por,
                $c->lugar,
                $c->tipo_formacion ?? 'N/A',
                $c->duracion ?? 'N/A',
                $c->forma ?? 'N/A',
                $c->medio ?? 'N/A',
                ucfirst($c->cupos),
                $c->limite_participantes ?? 'N/A',
                number_format($c->precio_afiliado ?? 0, 2),
                number_format($c->isv_afiliado ?? 0, 2),
                number_format($c->precio_no_afiliado ?? 0, 2),
                number_format($c->isv_no_afiliado ?? 0, 2),
                $c->participantes_count,
            ]);
        }

        return $detalle;
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Fecha',
            'Impartido Por',
            'Lugar',
            'Tipo de Formación',
            'Duración',
            'Modalidad',
            'Medio',
            'Cupos',
            'Límite de Participantes',
            'Precio Afiliado',
            'ISV Afiliado',
            'Precio No Afiliado',
            'ISV No Afiliado',
            'Total Participantes',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastColumn = 'P'; // Columna 16
                $lastRow = $sheet->getHighestRow();

                // Estilo para encabezados
                $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F81BD'], // Azul Excel clásico
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // Bordes para todos los datos
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Alto para encabezado
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Auto filtro
                $sheet->setAutoFilter('A1:' . $lastColumn . '1');
            },
        ];
    }
}
