<?php

namespace App\Exports;

use App\Models\Capacitacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ParticipantesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    protected $capacitacion;

    public function __construct(Capacitacion $capacitacion)
    {
        $this->capacitacion = $capacitacion;
    }

    public function collection()
    {
        return $this->capacitacion->participantes()->select(
            'identidad',
            'nombre_completo',
            'correo',
            'telefono',
            'empresa',
            'puesto',
            'edad',
            'nivel_educativo',
            'genero',
            'municipio',
            'ciudad',
            'afiliado',
            'precio',
            'isv',
            'total',
            'comprobante'
        )->get()->map(function ($p) {
            return [
                'identidad' => $p->identidad,
                'nombre_completo' => $p->nombre_completo,
                'correo' => $p->correo,
                'telefono' => $p->telefono,
                'empresa' => $p->empresa,
                'puesto' => $p->puesto,
                'edad' => $p->edad,
                'nivel_educativo' => $p->nivel_educativo,
                'genero' => $p->genero,
                'municipio' => $p->municipio,
                'ciudad' => $p->ciudad,
                'afiliado' => $p->afiliado ? 'Sí' : 'No',
                'precio' => number_format($p->precio, 2),
                'isv' => number_format($p->isv, 2),
                'total' => number_format($p->total, 2),
                'comprobante' => $p->comprobante,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Identidad',
            'Nombre Completo',
            'Correo',
            'Teléfono',
            'Empresa',
            'Puesto',
            'Edad',
            'Nivel Educativo',
            'Género',
            'Municipio',
            'Ciudad',
            'Afiliado',
            'Precio Base',
            'ISV',
            'Total a Pagar',
            'Comprobante',
        ];
    }

    public function startCell(): string
    {
        return 'A2'; // Comenzar encabezado en la fila 2 para dejar espacio al título
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $columnCount = 16;
                $lastColumn = chr(64 + $columnCount); // P (columna 16)
                $lastRow = $sheet->getHighestRow();

                // Título superior en la fila 1
                $titulo = 'PARTICIPANTES DE LA CAPACITACIÓN: ' . mb_strtoupper($this->capacitacion->nombre);
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->setCellValue("A1", $titulo);
                $sheet->getStyle("A1")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Estilo para encabezados (fila 2)
                $sheet->getStyle("A2:{$lastColumn}2")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF'],
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
                $sheet->getRowDimension(2)->setRowHeight(30);

                // Bordes para todo el contenido (incluyendo encabezados y datos)
                $sheet->getStyle("A2:{$lastColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // Activar auto-filtro
                $sheet->setAutoFilter("A2:{$lastColumn}2");
            }
        ];
    }
}
