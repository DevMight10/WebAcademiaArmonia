<?php

namespace App\Enums;

enum CategoriaInstructor: string
{
    case REGULAR = 'regular';
    case PREMIUM = 'premium';
    case INVITADO = 'invitado';

    /**
     * Get the cost factor for this category.
     */
    public function factorCosto(): float
    {
        return match($this) {
            self::REGULAR => 1.0,
            self::PREMIUM => 1.2,
            self::INVITADO => 1.0, // Variable según el instructor invitado
        };
    }

    /**
     * Get the label for this category.
     */
    public function label(): string
    {
        return match($this) {
            self::REGULAR => 'Regular',
            self::PREMIUM => 'Premium',
            self::INVITADO => 'Invitado',
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
