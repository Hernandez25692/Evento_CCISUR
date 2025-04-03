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
                'precio' => $p->precio_base,
                'isv' => $p->isv,
                'total' => $p->total,
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
            'Comprobante'
        ];
    }
}
