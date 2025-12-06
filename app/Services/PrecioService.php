<?php

namespace App\Services;

class PrecioService
{
    const PRECIO_BASE_POR_MINUTO = 25;  // Bs por minuto
    const DESCUENTO_MAXIMO = 45;        // Porcentaje máximo

    /**
     * Calcula el descuento basado en los minutos comprados.
     * Sistema escalonado: cada 300 minutos = 5% de descuento hasta máximo 45%.
     */
    public static function calcularDescuento(int $minutos): float
    {
        if ($minutos < 300) {
            return 0;
        }

        // Cada bloque de 300 minutos otorga 5% adicional
        $bloques = floor($minutos / 300);
        $descuento = $bloques * 5;

        // Tope de 45% para evitar pérdidas
        return min($descuento, self::DESCUENTO_MAXIMO);
    }

    /**
     * Calcula el subtotal sin aplicar descuentos.
     */
    public static function calcularSubtotal(int $minutos): float
    {
        return $minutos * self::PRECIO_BASE_POR_MINUTO;
    }

    /**
     * Calcula el monto del descuento en Bolivianos.
     */
    public static function calcularMontoDescuento(int $minutos): float
    {
        $subtotal = self::calcularSubtotal($minutos);
        $porcentajeDescuento = self::calcularDescuento($minutos);

        return $subtotal * ($porcentajeDescuento / 100);
    }

    /**
     * Calcula el total a pagar con descuento aplicado.
     */
    public static function calcularTotal(int $minutos): float
    {
        $subtotal = self::calcularSubtotal($minutos);
        $descuento = self::calcularMontoDescuento($minutos);

        return $subtotal - $descuento;
    }

    /**
     * Obtiene todos los paquetes predefinidos con sus precios.
     * Paquetes desde 300 hasta 2700 minutos (5 a 45 horas).
     */
    public static function obtenerPaquetes(): array
    {
        $paquetes = [];
        $minutosPaquetes = [300, 600, 900, 1200, 1500, 1800, 2100, 2400, 2700];

        foreach ($minutosPaquetes as $minutos) {
            $paquetes[] = [
                'minutos' => $minutos,
                'horas' => $minutos / 60,
                'subtotal' => self::calcularSubtotal($minutos),
                'porcentaje_descuento' => self::calcularDescuento($minutos),
                'monto_descuento' => self::calcularMontoDescuento($minutos),
                'total' => self::calcularTotal($minutos),
                'ahorro' => self::calcularMontoDescuento($minutos),
            ];
        }

        return $paquetes;
    }

    /**
     * Obtiene el nombre del paquete según cantidad de minutos.
     * Clasifica desde "Básico" hasta "Premium".
     */
    public static function obtenerNombrePaquete(int $minutos): string
    {
        return match(true) {
            $minutos < 300 => 'Personalizado',
            $minutos >= 2700 => 'Premium',
            $minutos >= 2100 => 'Experto',
            $minutos >= 1500 => 'Profesional',
            $minutos >= 900 => 'Avanzado',
            $minutos >= 600 => 'Intermedio',
            $minutos >= 300 => 'Básico',
            default => 'Personalizado',
        };
    }

    /**
     * Formatea un monto en Bs con separadores de miles.
     */
    public static function formatearMonto(float $monto): string
    {
        return number_format($monto, 2, ',', '.') . ' Bs';
    }
}
