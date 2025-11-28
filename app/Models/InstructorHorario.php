<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorHorario extends Model
{
    use HasFactory;

    protected $table = 'instructor_horarios';

    protected $fillable = [
        'instructor_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    /**
     * Get the instructor that owns the horario.
     */
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}
