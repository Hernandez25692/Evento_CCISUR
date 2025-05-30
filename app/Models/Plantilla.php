<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    use HasFactory;

    protected $fillable = [
        'capacitacion_id',
        'firma_1',
        'firma_2',
        'fondo',
        'fecha_emision',
        'orientacion',
        'nombre_firma_1',
        'nombre_firma_2',
        'titulo_convenio',
        'tipo_certificado',
        'fuente',

    ];


    public function capacitacion()
    {
        return $this->belongsTo(Capacitacion::class);
    }
}
