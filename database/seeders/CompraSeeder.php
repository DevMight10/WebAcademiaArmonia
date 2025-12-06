<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Compra;
use App\Models\DistribucionCredito;
use App\Models\Cliente;
use App\Models\Beneficiario;
use Carbon\Carbon;

class CompraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fecha base: 6 de diciembre de 2025
        $hoy = Carbon::create(2025, 12, 6, 12, 0, 0);

        // Obtener clientes y beneficiarios
        $ana = Cliente::whereHas('user', fn($q) => $q->where('email', 'ana_cliente@academiaarmonia.com'))->first();
        $pedro = Cliente::whereHas('user', fn($q) => $q->where('email', 'pedro_cliente@academiaarmonia.com'))->first();
        $laura = Cliente::whereHas('user', fn($q) => $q->where('email', 'laura_cliente@academiaarmonia.com'))->first();

        $sofia = Beneficiario::whereHas('user', fn($q) => $q->where('email', 'sofia_beneficiario@academiaarmonia.com'))->first();
        $diego = Beneficiario::whereHas('user', fn($q) => $q->where('email', 'diego_beneficiario@academiaarmonia.com'))->first();
        $valentina = Beneficiario::whereHas('user', fn($q) => $q->where('email', 'valentina_beneficiario@academiaarmonia.com'))->first();

        if (!$ana || !$pedro || !$laura || !$sofia || !$diego || !$valentina) {
            return; // Si no existen los datos necesarios, salir
        }

        // Compra 1: Ana - Aprobada hace 20 días
        $minutos1 = 240;
        $precio1 = 1.25;
        $subtotal1 = $minutos1 * $precio1;
        $porcentaje_desc1 = 0;
        $descuento1 = 0;
        $total1 = $subtotal1 - $descuento1;

        $compra1 = Compra::firstOrCreate(
            [
                'cliente_id' => $ana->id,
                'created_at' => $hoy->copy()->subDays(20),
            ],
            [
                'minutos_totales' => $minutos1,
                'precio_por_minuto' => $precio1,
                'porcentaje_descuento' => $porcentaje_desc1,
                'subtotal' => $subtotal1,
                'descuento' => $descuento1,
                'total' => $total1,
                'estado' => 'aprobada',
            ]
        );

        // Distribución para Sofía (120 min, 60 disponibles = 60 consumidos)
        DistribucionCredito::firstOrCreate(
            [
                'compra_id' => $compra1->id,
                'beneficiario_id' => $sofia->id,
            ],
            [
                'minutos_asignados' => 120,
                'minutos_disponibles' => 60,
                'estado' => 'activo',
            ]
        );

        // Distribución para Diego (120 min, 90 disponibles = 30 consumidos)
        DistribucionCredito::firstOrCreate(
            [
                'compra_id' => $compra1->id,
                'beneficiario_id' => $diego->id,
            ],
            [
                'minutos_asignados' => 120,
                'minutos_disponibles' => 90,
                'estado' => 'activo',
            ]
        );

        // Compra 2: Pedro - Aprobada hace 10 días
        $minutos2 = 360;
        $precio2 = 1.20;
        $subtotal2 = $minutos2 * $precio2;
        $porcentaje_desc2 = 5;
        $descuento2 = $subtotal2 * ($porcentaje_desc2 / 100);
        $total2 = $subtotal2 - $descuento2;

        $compra2 = Compra::firstOrCreate(
            [
                'cliente_id' => $pedro->id,
                'created_at' => $hoy->copy()->subDays(10),
            ],
            [
                'minutos_totales' => $minutos2,
                'precio_por_minuto' => $precio2,
                'porcentaje_descuento' => $porcentaje_desc2,
                'subtotal' => $subtotal2,
                'descuento' => $descuento2,
                'total' => $total2,
                'estado' => 'aprobada',
            ]
        );

        // Distribución para Valentina (360 min, 300 disponibles = 60 consumidos)
        DistribucionCredito::firstOrCreate(
            [
                'compra_id' => $compra2->id,
                'beneficiario_id' => $valentina->id,
            ],
            [
                'minutos_asignados' => 360,
                'minutos_disponibles' => 300,
                'estado' => 'activo',
            ]
        );

        // Compra 3: Laura - Pendiente (hoy)
        $minutos3 = 180;
        $precio3 = 1.25;
        $subtotal3 = $minutos3 * $precio3;
        $porcentaje_desc3 = 0;
        $descuento3 = 0;
        $total3 = $subtotal3 - $descuento3;

        Compra::firstOrCreate(
            [
                'cliente_id' => $laura->id,
                'created_at' => $hoy,
            ],
            [
                'minutos_totales' => $minutos3,
                'precio_por_minuto' => $precio3,
                'porcentaje_descuento' => $porcentaje_desc3,
                'subtotal' => $subtotal3,
                'descuento' => $descuento3,
                'total' => $total3,
                'estado' => 'pendiente',
            ]
        );
    }
}
