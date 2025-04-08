<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    use HasFactory;

    protected $fillable = [
        'capacitacion_id',
        'firma',
        'firma_2',
        'fondo',
        'fecha_emision',
        'orientacion',
        'firmante_1',
        'firmante_2',
        'mostrar_qr'
    ];


    public function capacitacion()
    {
        return $this->belongsTo(Capacitacion::class);
    }
}
