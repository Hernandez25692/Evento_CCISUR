<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Participante extends Model
{
    use HasFactory;
    protected $fillable = ['nombre_completo', 'correo', 'telefono', 'empresa', 'puesto', 'edad', 'identidad', 'nivel_educativo', 'genero', 'municipio', 'ciudad', 'capacitacion_id'];

    public function capacitacion()
    {
        return $this->belongsTo(Capacitacion::class);
    }
}

