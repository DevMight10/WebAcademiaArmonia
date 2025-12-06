<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders en orden de dependencia
        $this->call([
            RoleSeeder::class,          // 1. Primero los roles
            UserSeeder::class,          // 2. Usuarios admin y coordinador
            InstrumentoSeeder::class,   // 3. Catálogo de instrumentos
            InstructorSeeder::class,    // 4. Instructores con especialidades
            ClienteSeeder::class,       // 5. Clientes
            BeneficiarioSeeder::class,  // 6. Beneficiarios
            CompraSeeder::class,        // 7. Compras y distribuciones de crédito
            CitaSeeder::class,          // 8. Citas (requiere todo lo anterior)
        ]);
    }
}
