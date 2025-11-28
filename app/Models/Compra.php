<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'minutos_totales',
        'precio_por_minuto',
        'porcentaje_descuento',
        'subtotal',
        'descuento',
        'total',
        'estado',
    ];

    protected $casts = [
        'minutos_totales' => 'integer',
        'precio_por_minuto' => 'decimal:2',
        'porcentaje_descuento' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the cliente that owns the compra.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Get the distribuciones for the compra.
     */
    public function distribuciones()
    {
        return $this->hasMany(DistribucionCredito::class);
    }

    /**
     * Get the pago for the compra.
     */
    public function pago()
    {
        return $this->hasOne(Pago::class);
    }
}
