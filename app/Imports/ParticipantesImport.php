<?php

namespace App\Imports;

use App\Models\Participante;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Session;

class ParticipantesImport implements ToCollection
{
    protected $capacitacionId;

    public function __construct($capacitacionId)
    {
        $this->capacitacionId = $capacitacionId;
    }

    public function collection(Collection $rows)
    {
        // Validar que al menos una fila tenga 11 columnas esperadas (posición 0 a 10)
        $encabezado = $rows->first();

        if (!$encabezado || count($encabezado) < 11) {
            Session::flash('error', '❌ El archivo Excel no tiene la estructura correcta. Asegúrese de usar una plantilla con todos los campos necesarios.');
            return;
        }

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Saltar encabezado

            // Validar que la fila tenga al menos 11 columnas
            if (count($row) < 11) continue;

            $identidad = $row[0];

            $participante = Participante::firstOrCreate(
                ['identidad' => $identidad],
                [
                    'nombre_completo'   => $row[1],
                    'correo'            => $row[2],
                    'telefono'          => $row[3],
                    'empresa'           => $row[4],
                    'puesto'            => $row[5],
                    'edad'              => $row[6],
                    'nivel_educativo'   => $row[7],
                    'genero'            => $row[8],
                    'municipio'         => $row[9],
                    'ciudad'            => $row[10],
                ]
            );

            $participante->capacitaciones()->syncWithoutDetaching([$this->capacitacionId]);
        }
    }
}
