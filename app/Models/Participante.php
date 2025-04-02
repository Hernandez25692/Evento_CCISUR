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
        'afiliado',          // ✅ Nuevo campo booleano
        'precio',            // ✅ Precio base según tipo
        'isv',               // ✅ Impuesto sobre venta
        'total',             // ✅ Total a pagar (precio + ISV)
        'comprobante',       // ✅ Ruta del archivo adjunto
    ];

    // ✅ Relación muchos a muchos con Capacitacion
    public function capacitaciones()
    {
        return $this->belongsToMany(Capacitacion::class, 'capacitacion_participante', 'participante_id', 'capacitacion_id');
    }
}
