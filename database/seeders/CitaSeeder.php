<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cita;
use App\Models\Beneficiario;
use App\Models\Instructor;
use App\Models\Instrumento;
use App\Models\DistribucionCredito;
use Carbon\Carbon;

class CitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fecha base: 6 de diciembre de 2025
        $hoy = Carbon::create(2025, 12, 6, 12, 0, 0);

        // Obtener beneficiarios
        $sofia = Beneficiario::whereHas('user', fn($q) => $q->where('email', 'sofia_beneficiario@academiaarmonia.com'))->first();
        $diego = Beneficiario::whereHas('user', fn($q) => $q->where('email', 'diego_beneficiario@academiaarmonia.com'))->first();
        $valentina = Beneficiario::whereHas('user', fn($q) => $q->where('email', 'valentina_beneficiario@academiaarmonia.com'))->first();

        // Obtener instructores
        $carlos = Instructor::whereHas('user', fn($q) => $q->where('email', 'carlos_instructor@academiaarmonia.com'))->first();
        $maria = Instructor::whereHas('user', fn($q) => $q->where('email', 'maria_instructor@academiaarmonia.com'))->first();
        $juan = Instructor::whereHas('user', fn($q) => $q->where('email', 'juan_instructor@academiaarmonia.com'))->first();

        // Obtener instrumentos
        $piano = Instrumento::where('nombre', 'Piano')->first();
        $guitarra = Instrumento::where('nombre', 'LIKE', '%Guitarra%')->first();
        $violin = Instrumento::where('nombre', 'Violín')->first();
        $saxofon = Instrumento::where('nombre', 'Saxofón')->first();
        $bateria = Instrumento::where('nombre', 'Batería')->first();

        // Obtener distribuciones de crédito
        $dist1 = DistribucionCredito::where('beneficiario_id', $sofia->id)->first();
        $dist2 = DistribucionCredito::where('beneficiario_id', $diego->id)->first();
        $dist3 = DistribucionCredito::where('beneficiario_id', $valentina->id)->first();

        if (!$sofia || !$diego || !$valentina || !$carlos || !$maria || !$juan) {
            return; // Si no existen los datos necesarios, salir
        }

        // ========== CITAS COMPLETADAS (PASADAS) ==========

        // Cita 1: Sofía - Piano con Carlos - Hace 15 días
        if ($piano && $dist1) {
            Cita::firstOrCreate(
                [
                    'beneficiario_id' => $sofia->id,
                    'instructor_id' => $carlos->id,
                    'fecha_hora' => $hoy->copy()->subDays(15)->setTime(10, 0),
                ],
                [
                    'instrumento_id' => $piano->id,
                    'distribucion_credito_id' => $dist1->id,
                    'duracion_minutos' => 60,
                    'minutos_consumidos' => 60,
                    'estado' => 'completada',
                    'observaciones' => null,
                ]
            );
        }

        // Cita 2: Diego - Guitarra con Carlos - Hace 12 días
        if ($guitarra && $dist2) {
            Cita::firstOrCreate(
                [
                    'beneficiario_id' => $diego->id,
                    'instructor_id' => $carlos->id,
                    'fecha_hora' => $hoy->copy()->subDays(12)->setTime(14, 0),
                ],
                [
                    'instrumento_id' => $guitarra->id,
                    'distribucion_credito_id' => $dist2->id,
                    'duracion_minutos' => 30,
                    'minutos_consumidos' => 30,
                    'estado' => 'completada',
                    'observaciones' => null,
                ]
            );
        }

        // Cita 3: Valentina - Violín con María - Hace 5 días
        if ($violin && $dist3) {
            Cita::firstOrCreate(
                [
                    'beneficiario_id' => $valentina->id,
                    'instructor_id' => $maria->id,
                    'fecha_hora' => $hoy->copy()->subDays(5)->setTime(16, 0),
                ],
                [
                    'instrumento_id' => $violin->id,
                    'distribucion_credito_id' => $dist3->id,
                    'duracion_minutos' => 60,
                    'minutos_consumidos' => 60,
                    'estado' => 'completada',
                    'observaciones' => null,
                ]
            );
        }

        // ========== CITAS CONFIRMADAS (FUTURAS) ==========

        // Cita 4: Sofía - Piano con Carlos - En 2 días
        if ($piano && $dist1) {
            Cita::firstOrCreate(
                [
                    'beneficiario_id' => $sofia->id,
                    'instructor_id' => $carlos->id,
                    'fecha_hora' => $hoy->copy()->addDays(2)->setTime(10, 0),
                ],
                [
                    'instrumento_id' => $piano->id,
                    'distribucion_credito_id' => $dist1->id,
                    'duracion_minutos' => 60,
                    'minutos_consumidos' => 0,
                    'estado' => 'confirmada',
                    'observaciones' => null,
                ]
            );
        }

        // Cita 5: Valentina - Saxofón con María - En 5 días
        if ($saxofon && $dist3) {
            Cita::firstOrCreate(
                [
                    'beneficiario_id' => $valentina->id,
                    'instructor_id' => $maria->id,
                    'fecha_hora' => $hoy->copy()->addDays(5)->setTime(15, 0),
                ],
                [
                    'instrumento_id' => $saxofon->id,
                    'distribucion_credito_id' => $dist3->id,
                    'duracion_minutos' => 60,
                    'minutos_consumidos' => 0,
                    'estado' => 'confirmada',
                    'observaciones' => null,
                ]
            );
        }

        // ========== CITA PENDIENTE (FUTURA) ==========

        // Cita 6: Diego - Batería con Juan - En 7 días
        if ($bateria && $dist2) {
            Cita::firstOrCreate(
                [
                    'beneficiario_id' => $diego->id,
                    'instructor_id' => $juan->id,
                    'fecha_hora' => $hoy->copy()->addDays(7)->setTime(11, 0),
                ],
                [
                    'instrumento_id' => $bateria->id,
                    'distribucion_credito_id' => $dist2->id,
                    'duracion_minutos' => 60,
                    'minutos_consumidos' => 0,
                    'estado' => 'pendiente',
                    'observaciones' => null,
                ]
            );
        }
    }
}
