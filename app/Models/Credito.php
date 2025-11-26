<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    protected $fillable = [
        'estudiante_id',
        'minutos_totales',
        'minutos_usados',
        'minutos_disponibles',
    ];
}
