<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'compra_id',
        'metodo_pago',
        'monto',
        'comprobante',
        'fecha_solicitud',
        'fecha_verificacion',
        'verificado_por',
        'estado',
        'notas',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_solicitud' => 'datetime',
        'fecha_verificacion' => 'datetime',
    ];

    /**
     * Get the compra that owns the pago.
     */
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    /**
     * Get the user who verified the payment.
     */
    public function verificador()
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }
}
