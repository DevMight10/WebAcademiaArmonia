<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ci',
        'telefono',
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

    /**
     * Get the beneficiario associated with this cliente (if exists)
     */
    public function beneficiario()
    {
        return $this->hasOneThrough(
            Beneficiario::class,
            User::class,
            'id', // Foreign key on users table
            'user_id', // Foreign key on beneficiarios table
            'user_id', // Local key on clientes table
            'id' // Local key on users table
        );
    }

    /**
     * Get or create beneficiario for this cliente
     */
    public function getOrCreateBeneficiario()
    {
        $beneficiario = Beneficiario::where('user_id', $this->user_id)->first();
        
        if (!$beneficiario) {
            $beneficiario = Beneficiario::create([
                'user_id' => $this->user_id,
                'ci' => $this->ci ?? 'N/A', // Use cliente's CI or default
                'telefono' => $this->telefono ?? null,
                'fecha_nacimiento' => now()->subYears(18), // Default age
                'nivel_educativo' => 'No especificado'
            ]);
        }
        
        return $beneficiario;
    }
}
