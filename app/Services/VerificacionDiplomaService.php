<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Código único de verificación por diploma emitido. No existe un modelo
 * "Diploma": la fila que representa "este participante tiene este diploma
 * en esta capacitación" es la tabla pivote capacitacion_participante, así
 * que el código vive ahí en vez de crear una tabla/modelo nuevo.
 */
class VerificacionDiplomaService
{
    /**
     * Devuelve el código de verificación de un diploma, generándolo la
     * primera vez que se necesita (p. ej. al generar el PDF) y dejándolo
     * fijo para siempre a partir de ahí.
     */
    public static function codigoPara(int $capacitacionId, int $participanteId): string
    {
        $fila = DB::table('capacitacion_participante')
            ->where('capacitacion_id', $capacitacionId)
            ->where('participante_id', $participanteId)
            ->first();

        if ($fila && $fila->codigo_verificacion) {
            return $fila->codigo_verificacion;
        }

        $codigo = (string) Str::uuid();

        DB::table('capacitacion_participante')
            ->where('capacitacion_id', $capacitacionId)
            ->where('participante_id', $participanteId)
            ->update(['codigo_verificacion' => $codigo]);

        return $codigo;
    }

    /**
     * Busca el diploma asociado a un código de verificación escaneado.
     *
     * @return array{capacitacion_id:int, participante_id:int, habilitado_diploma:bool}|null
     */
    public static function localizar(string $codigo): ?array
    {
        $fila = DB::table('capacitacion_participante')
            ->where('codigo_verificacion', $codigo)
            ->first();

        if (!$fila) {
            return null;
        }

        return [
            'capacitacion_id' => (int) $fila->capacitacion_id,
            'participante_id' => (int) $fila->participante_id,
            'habilitado_diploma' => (bool) $fila->habilitado_diploma,
        ];
    }
}
