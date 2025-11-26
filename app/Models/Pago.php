<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'compra_id',
        'monto',
        'metodo_pago',
        'comprobante',
        'estado',
        'verificado_por',
        'fecha_verificacion',
    ];
}
