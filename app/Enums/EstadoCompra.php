<?php

namespace App\Enums;

enum EstadoCompra: string
{
    case PENDIENTE = 'pendiente';
    case PAGO_SOLICITADO = 'pago_solicitado';
    case PAGADO_Y_CONFIRMADO = 'pagado_y_confirmado';
    case RECHAZADO = 'rechazado';
    case CANCELADO = 'cancelado';

    /**
     * Get the label for this status.
     */
    public function label(): string
    {
        return match($this) {
            self::PENDIENTE => 'Pendiente',
            self::PAGO_SOLICITADO => 'Pago Solicitado',
            self::PAGADO_Y_CONFIRMADO => 'Pagado y Confirmado',
            self::RECHAZADO => 'Rechazado',
            self::CANCELADO => 'Cancelado',
        };
    }

    /**
     * Get the color class for this status.
     */
    public function colorClass(): string
    {
        return match($this) {
            self::PENDIENTE => 'text-yellow-600 bg-yellow-50',
            self::PAGO_SOLICITADO => 'text-blue-600 bg-blue-50',
            self::PAGADO_Y_CONFIRMADO => 'text-green-600 bg-green-50',
            self::RECHAZADO => 'text-red-600 bg-red-50',
            self::CANCELADO => 'text-gray-600 bg-gray-50',
        };
    }

    /**
     * Get all statuses as array.
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
