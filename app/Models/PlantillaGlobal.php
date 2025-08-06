<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantillaGlobal extends Model
{
    use HasFactory;
    protected $table = 'plantillas_globales';

    protected $fillable = [
        'nombre',
        'fondo',
        'firma_1',
        'firma_2',
        'nombre_firma_1',
        'nombre_firma_2',
        'orientacion',
        'tipo_certificado',
        'titulo_convenio',
        'fecha_emision',
    ];
}
