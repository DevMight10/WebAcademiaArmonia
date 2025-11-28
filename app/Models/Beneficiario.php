<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ci',
        'telefono',
    ];

    /**
     * Get the user associated with the beneficiario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the distribuciones de credito for the beneficiario.
     */
    public function distribuciones()
    {
        return $this->hasMany(DistribucionCredito::class);
    }

    /**
     * Get total available credits.
     */
    public function getSaldoCreditosAttribute()
    {
        return $this->distribuciones()
            ->where('estado', 'activo')
            ->sum('minutos_disponibles');
    }
}
