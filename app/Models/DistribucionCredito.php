<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistribucionCredito extends Model
{
    use HasFactory;

    protected $table = 'distribucion_creditos';

    protected $fillable = [
        'compra_id',
        'beneficiario_id',
        'minutos_asignados',
        'minutos_disponibles',
        'estado',
    ];

    protected $casts = [
        'minutos_asignados' => 'integer',
        'minutos_disponibles' => 'integer',
    ];

    /**
     * Get the compra that owns the distribucion.
     */
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    /**
     * Get the beneficiario that owns the distribucion.
     */
    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class);
    }
}
