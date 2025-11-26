<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = [
        'cliente_id',
        'paquete_id',
        'total_minutos',
        'precio_total',
        'estado',
    ];
}
