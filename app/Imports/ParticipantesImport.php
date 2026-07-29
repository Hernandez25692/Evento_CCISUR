<?php

namespace App\Imports;

use App\Models\Capacitacion;
use App\Models\Participante;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Database\UniqueConstraintViolationException;

class ParticipantesImport implements ToCollection
{
    protected $capacitacionId;

    /**
     * Mensaje general si el archivo completo es inválido (estructura,
     * capacitación inexistente, etc.) — a diferencia de $errores, que son
     * problemas de filas puntuales dentro de un archivo por lo demás válido.
     */
    public ?string $errorGeneral = null;

    public int $importados = 0;

    public int $yaRegistrados = 0;

    /** @var string[] Un mensaje legible por cada fila que no se pudo importar. */
    public array $errores = [];

    public function __construct($capacitacionId)
    {
        $this->capacitacionId = $capacitacionId;
    }

    public function collection(Collection $rows)
    {
        if ($rows->count() < 2) {
            $this->errorGeneral = 'El archivo no contiene suficientes filas.';
            return;
        }

        $encabezado = $rows[1]; // Fila 1 es el encabezado
        if (!$encabezado || count($encabezado) < 11) {
            $this->errorGeneral = 'La estructura del archivo Excel no es válida.';
            return;
        }

        $capacitacion = Capacitacion::find($this->capacitacionId);
        if (!$capacitacion) {
            $this->errorGeneral = 'Capacitación no encontrada.';
            return;
        }

        $actuales = $capacitacion->participantes()->count();
        $limite = $capacitacion->limite_participantes ?? 0;
        $esLimitado = $capacitacion->cupos === 'limitado';

        foreach ($rows as $index => $row) {
            if ($index < 2 || count($row) < 11 || empty($row[0])) continue;

            // La fila del Excel es 1-indexada y la primera es el encabezado,
            // así que la fila real en la hoja es $index (ya viene 1-indexado
            // por la librería) + 1.
            $numeroFila = $index + 1;

            if ($esLimitado && ($actuales + $this->importados) >= $limite) {
                $this->errores[] = "Fila {$numeroFila}: se alcanzó el límite de cupos, esta fila y las siguientes no se importaron.";
                break;
            }

            $identidad = trim($row[0]);
            $nombre = trim($row[1]);
            $correo = trim($row[2]);

            // Evita que un correo repetido tumbe todo el import: si ya hay
            // un participante con esa identidad, esta fila lo referencia
            // (comportamiento normal); si el correo ya pertenece a OTRA
            // identidad, es un dato inconsistente en el archivo y se salta
            // esa fila puntual en vez de abortar el resto.
            $participanteExistente = Participante::where('identidad', $identidad)->first();

            if (!$participanteExistente && $correo !== '') {
                $correoUsadoPorOtro = Participante::where('correo', $correo)
                    ->where('identidad', '!=', $identidad)
                    ->first();

                if ($correoUsadoPorOtro) {
                    $this->errores[] = "Fila {$numeroFila} ({$nombre}): el correo \"{$correo}\" ya está registrado para otro participante (identidad {$correoUsadoPorOtro->identidad}). Corrige el correo o la identidad en el Excel.";
                    continue;
                }
            }

            $datos = [
                'nombre_completo'   => $nombre,
                'correo'            => $correo,
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

            try {
                $participante = Participante::firstOrCreate(['identidad' => $identidad], $datos);
            } catch (UniqueConstraintViolationException $e) {
                // Red de seguridad ante condiciones de carrera u otra
                // restricción única que no se haya validado arriba: se
                // reporta la fila puntual en vez de tumbar todo el import.
                $this->errores[] = "Fila {$numeroFila} ({$nombre}): no se pudo guardar, el dato ya existe en otro participante (identidad o correo duplicado).";
                continue;
            }

            if ($participante->capacitaciones->contains($this->capacitacionId)) {
                $this->yaRegistrados++;
                continue;
            }

            $participante->capacitaciones()->attach($this->capacitacionId);
            $this->importados++;
        }
    }
}
