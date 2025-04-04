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
        if ($rows->count() < 2) {
            Session::flash('error', '❌ El archivo no contiene suficientes filas.');
            return;
        }

        $encabezado = $rows[1]; // La fila 1 (índice 0) es el título, la fila 2 (índice 1) son los encabezados reales

        if (!$encabezado || count($encabezado) < 11) {
            Session::flash('error', '❌ El archivo Excel no tiene la estructura correcta. Asegúrese de usar una plantilla con todos los campos necesarios.');
            return;
        }

        foreach ($rows as $index => $row) {
            if ($index < 2) continue; // Saltar título y encabezado

            if (count($row) < 11 || empty($row[0])) continue; // Asegurar identidad y estructura mínima

            $identidad = trim($row[0]);

            $datos = [
                'nombre_completo'   => trim($row[1]),
                'correo'            => trim($row[2]),
                'telefono'          => trim($row[3]),
                'empresa'           => trim($row[4]),
                'puesto'            => trim($row[5]),
                'edad'              => intval($row[6]),
                'nivel_educativo'   => trim($row[7]),
                'genero'            => trim($row[8]),
                'municipio'         => trim($row[9]),
                'ciudad'            => trim($row[10]),
            ];

            if (isset($row[11])) $datos['afiliado'] = strtolower(trim($row[11])) === 'sí' ? 1 : 0;
            if (isset($row[12])) $datos['precio'] = floatval($row[12]);
            if (isset($row[13])) $datos['isv'] = floatval($row[13]);
            if (isset($row[14])) $datos['total'] = floatval($row[14]);
            if (isset($row[15])) $datos['comprobante'] = trim($row[15]);

            $participante = Participante::firstOrCreate(
                ['identidad' => $identidad],
                $datos
            );

            $participante->capacitaciones()->syncWithoutDetaching([$this->capacitacionId]);
        }
    }
}
