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
        $encabezado = $rows->first();

        if (!$encabezado || count($encabezado) < 11) {
            Session::flash('error', '❌ El archivo Excel no tiene la estructura correcta. Asegúrese de usar una plantilla con todos los campos necesarios.');
            return;
        }

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Saltar encabezado

            if (count($row) < 11) continue;

            $identidad = $row[0];

            $datos = [
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
            ];

            // Si hay más columnas, agregamos los campos nuevos
            if (isset($row[11])) $datos['afiliado'] = strtolower($row[11]) === 'sí' ? 1 : 0;
            if (isset($row[12])) $datos['precio'] = $row[12];
            if (isset($row[13])) $datos['isv'] = $row[13];
            if (isset($row[14])) $datos['total'] = $row[14];
            if (isset($row[15])) $datos['comprobante'] = $row[15]; // Esto es solo la ruta/nombre, no el archivo real

            $participante = Participante::firstOrCreate(
                ['identidad' => $identidad],
                $datos
            );

            $participante->capacitaciones()->syncWithoutDetaching([$this->capacitacionId]);
        }
    }
}
