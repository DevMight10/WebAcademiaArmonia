<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $fillable = [
        'cita_id',
        'minutos_consumidos',
        'modalidad',
        'notas',
    ];
}
