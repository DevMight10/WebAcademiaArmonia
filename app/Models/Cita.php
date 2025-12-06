<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    protected $fillable = [
        'beneficiario_id',
        'instructor_id',
        'instrumento_id',
        'distribucion_credito_id',
        'fecha_hora',
        'duracion_minutos',
        'minutos_consumidos',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    /**
     * Relación con Beneficiario
     */
    public function beneficiario(): BelongsTo
    {
        return $this->belongsTo(Beneficiario::class);
    }

    /**
     * Relación con Instructor
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Relación con Instrumento
     */
    public function instrumento(): BelongsTo
    {
        return $this->belongsTo(Instrumento::class);
    }

    /**
     * Relación con Distribución de Crédito
     */
    public function distribucionCredito(): BelongsTo
    {
        return $this->belongsTo(DistribucionCredito::class);
    }

    /**
     * Scope para citas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para citas confirmadas
     */
    public function scopeConfirmadas($query)
    {
        return $query->where('estado', 'confirmada');
    }

    /**
     * Scope para citas completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    /**
     * Verificar si la cita puede ser cancelada
     */
    public function puedeCancelarse(): bool
    {
        return $this->estado === 'pendiente';
    }
}
