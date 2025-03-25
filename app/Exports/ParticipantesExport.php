<?php

namespace App\Exports;

use App\Models\Capacitacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipantesExport implements FromCollection, WithHeadings
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
            'ciudad'
        )->get();
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
            'Ciudad'
        ];
    }
}
