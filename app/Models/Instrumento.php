<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrumento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'factor_costo',
        'estado',
    ];

    protected $casts = [
        'factor_costo' => 'decimal:2',
        'estado' => 'boolean',
    ];
}
