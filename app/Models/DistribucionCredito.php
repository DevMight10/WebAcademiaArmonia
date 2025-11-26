<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribucionCredito extends Model
{
    protected $fillable = [
        'compra_id',
        'estudiante_id',
        'minutos_asignados',
    ];
}
