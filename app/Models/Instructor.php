<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $table = 'instructores';

    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'telefono',
        'email',
        'categoria',
        'factor_costo',
        'estado',
    ];

    protected $casts = [
        'factor_costo' => 'decimal:2',
        'estado' => 'boolean',
    ];

    /**
     * Get the especialidades for the instructor.
     */
    public function especialidades()
    {
        return $this->hasMany(InstructorEspecialidad::class);
    }

    /**
     * Get the horarios for the instructor.
     */
    public function horarios()
    {
        return $this->hasMany(InstructorHorario::class);
    }
}
