<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorEspecialidad extends Model
{
    use HasFactory;

    protected $table = 'instructor_especialidades';

    protected $fillable = [
        'instructor_id',
        'instrumento_id',
    ];

    /**
     * Get the instructor that owns the especialidad.
     */
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Get the instrumento for this especialidad.
     */
    public function instrumento()
    {
        return $this->belongsTo(Instrumento::class);
    }
}
