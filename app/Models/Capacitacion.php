<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capacitacion extends Model
{
    use HasFactory;

    protected $table = 'capacitaciones'; // Asegurar que el nombre de la tabla es correcto

    protected $fillable = ['nombre', 'lugar', 'fecha', 'impartido_por', 'descripcion', 'imagen'];

    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'capacitacion_participante', 'capacitacion_id', 'participante_id');
    }


    public function plantilla()
    {
        return $this->hasOne(Plantilla::class);
    }
}
