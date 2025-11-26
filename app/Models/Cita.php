<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'estudiante_id',
        'instructor_id',
        'instrumento_id',
        'fecha_hora',
        'duracion_estimada',
        'estado',
    ];
}
