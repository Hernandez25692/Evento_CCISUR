<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescargaDiplomaLog extends Model
{
    public $timestamps = false;

    protected $table = 'descargas_diplomas_log';

    protected $fillable = [
        'capacitacion_id',
        'participante_id',
        'ip',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
