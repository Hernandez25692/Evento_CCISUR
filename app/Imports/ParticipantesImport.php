<?php

namespace App\Imports;

use App\Models\Capacitacion;
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

        $encabezado = $rows[1]; // Fila 1 es el encabezado
        if (!$encabezado || count($encabezado) < 11) {
            Session::flash('error', '❌ La estructura del archivo Excel no es válida.');
            return;
        }

        $capacitacion = Capacitacion::find($this->capacitacionId);
        if (!$capacitacion) {
            Session::flash('error', '❌ Capacitación no encontrada.');
            return;
        }

        $actuales = $capacitacion->participantes()->count();
        $limite = $capacitacion->limite_participantes ?? 0;
        $esLimitado = $capacitacion->cupos === 'limitado';

        $importados = 0;

        foreach ($rows as $index => $row) {
            if ($index < 2 || count($row) < 11 || empty($row[0])) continue;

            if ($esLimitado && ($actuales + $importados) >= $limite) {
                Session::flash('warning', '⚠️ Se alcanzó el límite de cupos. Solo se importaron ' . $importados . ' participantes.');
                break;
            }

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

            $participante = Participante::firstOrCreate(['identidad' => $identidad], $datos);

            // Verificar si ya está vinculado
            if (!$participante->capacitaciones->contains($this->capacitacionId)) {
                $participante->capacitaciones()->attach($this->capacitacionId);
                $importados++;
            }
        }

        Session::flash('success', "✅ Se importaron correctamente $importados participantes.");
    }
}
