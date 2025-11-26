<?php

namespace App\Enums;

enum ModalidadClase: string
{
    case INDIVIDUAL = 'individual';
    case DUO = 'duo';
    case GRUPAL = 'grupal';
    case MASTERCLASS = 'masterclass';

    /**
     * Get the cost factor for this modality.
     */
    public function factorCosto(): float
    {
        return match($this) {
            self::INDIVIDUAL => 1.0,
            self::DUO => 0.75,
            self::GRUPAL => 0.60,
            self::MASTERCLASS => 1.0, // Variable según el instructor
        };
    }

    /**
     * Get the label for this modality.
     */
    public function label(): string
    {
        return match($this) {
            self::INDIVIDUAL => 'Individual',
            self::DUO => 'Dúo',
            self::GRUPAL => 'Grupal (3-4 personas)',
            self::MASTERCLASS => 'Masterclass',
        };
    }

    /**
     * Get all modalities as array.
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
