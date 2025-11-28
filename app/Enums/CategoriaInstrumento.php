<?php

namespace App\Enums;

enum CategoriaInstrumento: string
{
    case BASICO = 'basico';
    case INTERMEDIO = 'intermedio';
    case AVANZADO = 'avanzado';
    case ESPECIALIZADO = 'especializado';

    /**
     * Get the cost factor for this category.
     */
    public function factorCosto(): float
    {
        return match($this) {
            self::BASICO => 1.0,
            self::INTERMEDIO => 1.0,
            self::AVANZADO => 1.15,
            self::ESPECIALIZADO => 1.25,
        };
    }

    /**
     * Get the label for this category.
     */
    public function label(): string
    {
        return match($this) {
            self::BASICO => 'Básico',
            self::INTERMEDIO => 'Intermedio',
            self::AVANZADO => 'Avanzado',
            self::ESPECIALIZADO => 'Especializado',
        };
    }

    /**
     * Get all categories as array.
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
