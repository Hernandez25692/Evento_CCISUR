<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    use HasFactory;

    protected $fillable = ['capacitacion_id', 'firma', 'fondo', 'fecha_emision', 'orientacion'];

    public function capacitacion()
    {
        return $this->belongsTo(Capacitacion::class);
    }
}


