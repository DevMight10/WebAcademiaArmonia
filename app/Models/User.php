<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role of the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the cliente profile if user is a cliente.
     */
    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    /**
     * Get the beneficiario profile if user is a beneficiario.
     */
    public function beneficiario()
    {
        return $this->hasOne(Beneficiario::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleSlug): bool
    {
        // TODO: Implement role checking logic
        return false;
    }

    /**
     * Check if user is an administrador.
     */
    public function isAdministrador(): bool
    {
        // TODO: Implement logic
        return false;
    }

    /**
     * Check if user is a coordinador.
     */
    public function isCoordinador(): bool
    {
        // TODO: Implement logic
        return false;
    }

    /**
     * Check if user is a cliente.
     */
    public function isCliente(): bool
    {
        // TODO: Implement logic
        return false;
    }

    /**
     * Check if user is an estudiante/beneficiario.
     */
    public function isEstudiante(): bool
    {
        // TODO: Implement logic
        return false;
    }
}
