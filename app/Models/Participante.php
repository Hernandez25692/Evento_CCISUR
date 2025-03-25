<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'correo',
        'telefono',
        'empresa',
        'puesto',
        'edad',
        'identidad',
        'nivel_educativo',
        'genero',
        'municipio',
        'ciudad',
    ];

    // ✅ Relación muchos a muchos con Capacitacion
    public function capacitaciones()
    {
        return $this->belongsToMany(Capacitacion::class, 'capacitacion_participante', 'participante_id', 'capacitacion_id');
    }
}
