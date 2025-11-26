<?php

namespace App\Enums;

enum MetodoPago: string
{
    case TRANSFERENCIA = 'transferencia';
    case QR = 'qr';
    case EFECTIVO = 'efectivo';

    /**
     * Get the label for this payment method.
     */
    public function label(): string
    {
        return match($this) {
            self::TRANSFERENCIA => 'Transferencia Bancaria',
            self::QR => 'Código QR',
            self::EFECTIVO => 'Efectivo',
        };
    }

    /**
     * Get instructions for this payment method.
     */
    public function instrucciones(): string
    {
        return match($this) {
            self::TRANSFERENCIA => 'Realizar transferencia a la cuenta bancaria indicada y enviar comprobante.',
            self::QR => 'Escanear el código QR y realizar el pago.',
            self::EFECTIVO => 'Realizar el depósito en la caja de recepción de la academia.',
        };
    }

    /**
     * Get all payment methods as array.
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
