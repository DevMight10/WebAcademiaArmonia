<?php

namespace App\Services;

use App\Models\Beneficiario;

class ConsumoCreditoService
{
    /**
     * Calculate credit consumption for a class.
     *
     * Consumo = Duración_Real × Factor_Instrumento × Factor_Modalidad × Factor_Instructor
     */
    public function calcularConsumo(
        int $duracionMinutos,
        float $factorInstrumento = 1.0,
        float $factorModalidad = 1.0,
        float $factorInstructor = 1.0
    ): int {
        $consumo = $duracionMinutos * $factorInstrumento * $factorModalidad * $factorInstructor;
        return (int) round($consumo);
    }

    /**
     * Check if beneficiary has sufficient credits.
     */
    public function tieneCreditosSuficientes(Beneficiario $beneficiario, int $minutosRequeridos): bool
    {
        return $beneficiario->saldo_creditos >= $minutosRequeridos;
    }

    /**
     * Consume credits from beneficiary balance.
     */
    public function consumirCreditos(Beneficiario $beneficiario, int $minutos): void
    {
        // TODO: Implement actual credit consumption
        // This will be implemented in Part 2 when we handle class sessions
    }
}
