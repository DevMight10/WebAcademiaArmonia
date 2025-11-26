<?php

namespace App\Services;

use App\Models\Compra;
use App\Models\Beneficiario;
use App\Models\DistribucionCredito;
use Illuminate\Support\Facades\DB;

class DistribucionCreditoService
{
    /**
     * Distribute credits among beneficiaries after payment confirmation.
     */
    public function distribuirCreditos(Compra $compra, array $beneficiariosData): void
    {
        DB::transaction(function () use ($compra, $beneficiariosData) {
            foreach ($beneficiariosData as $beneficiarioData) {
                // Create or find beneficiario
                $beneficiario = Beneficiario::firstOrCreate(
                    ['ci' => $beneficiarioData['ci']],
                    [
                        'nombre' => $beneficiarioData['nombre'],
                        'apellido' => $beneficiarioData['apellido'],
                        'telefono' => $beneficiarioData['telefono'],
                        'email' => $beneficiarioData['email'],
                    ]
                );

                // Create credit distribution
                DistribucionCredito::create([
                    'compra_id' => $compra->id,
                    'beneficiario_id' => $beneficiario->id,
                    'minutos_asignados' => $beneficiarioData['minutos'],
                    'minutos_disponibles' => $beneficiarioData['minutos'],
                    'estado' => 'activo',
                ]);
            }
        });
    }

    /**
     * Transfer credits between beneficiaries.
     */
    public function transferirCreditos(
        Beneficiario $origen,
        Beneficiario $destino,
        int $minutos
    ): bool {
        // TODO: Implement credit transfer logic
        // Verify same original purchase, sufficient balance, etc.
        return true;
    }
}
