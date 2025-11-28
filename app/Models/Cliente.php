<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'ci',
        'telefono',
        'email',
    ];

    /**
     * Get the user associated with the cliente.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the compras for the cliente.
     */
    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
}
