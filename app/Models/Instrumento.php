<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instrumento extends Model
{
    protected $fillable = [
        'nombre',
        'tipo',
        'tarifa_extra',
        'disponible_alquiler',
    ];
}
