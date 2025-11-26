<?php

namespace App\Services;

class CalculoDescuentoService
{
    const PRECIO_BASE_POR_MINUTO = 25; // Bs por minuto
    const DESCUENTO_MAXIMO = 45; // 45% máximo

    /**
     * Calculate discount percentage based on total minutes purchased.
     *
     * - 300-599 min: 5% descuento
     * - 600-899 min: 10% descuento
     * - 900-1,199 min: 15% descuento
     * - Incremento de 5% cada 300 minutos adicionales
     * - Máximo 45% descuento (≥2,700 minutos)
     */
    public function calcularPorcentajeDescuento(int $minutos): float
    {
        if ($minutos < 300) {
            return 0;
        }

        if ($minutos >= 2700) {
            return self::DESCUENTO_MAXIMO;
        }

        // Calcular descuento: 5% por cada tramo de 300 minutos
        $tramos = floor($minutos / 300);
        $porcentaje = $tramos * 5;

        return min($porcentaje, self::DESCUENTO_MAXIMO);
    }

    /**
     * Calculate total price for a credit purchase.
     */
    public function calcularPrecioCompra(int $minutos): array
    {
        $subtotal = $minutos * self::PRECIO_BASE_POR_MINUTO;
        $porcentajeDescuento = $this->calcularPorcentajeDescuento($minutos);
        $descuento = $subtotal * ($porcentajeDescuento / 100);
        $total = $subtotal - $descuento;

        return [
            'minutos' => $minutos,
            'precio_por_minuto' => self::PRECIO_BASE_POR_MINUTO,
            'subtotal' => round($subtotal, 2),
            'porcentaje_descuento' => $porcentajeDescuento,
            'descuento' => round($descuento, 2),
            'total' => round($total, 2),
        ];
    }

    /**
     * Get all available packages with their discounts.
     */
    public function obtenerPaquetesDisponibles(): array
    {
        $paquetes = [
            ['minutos' => 30, 'nombre' => 'Paquete Inicial'],
            ['minutos' => 300, 'nombre' => 'Paquete Básico'],
            ['minutos' => 600, 'nombre' => 'Paquete Intermedio'],
            ['minutos' => 900, 'nombre' => 'Paquete Avanzado'],
            ['minutos' => 1200, 'nombre' => 'Paquete Plus'],
            ['minutos' => 1500, 'nombre' => 'Paquete Pro'],
            ['minutos' => 1800, 'nombre' => 'Paquete Expert'],
            ['minutos' => 2100, 'nombre' => 'Paquete Master'],
            ['minutos' => 2400, 'nombre' => 'Paquete Elite'],
            ['minutos' => 2700, 'nombre' => 'Paquete Premium'],
        ];

        return collect($paquetes)->map(function ($paquete) {
            return array_merge($paquete, $this->calcularPrecioCompra($paquete['minutos']));
        })->toArray();
    }
}
