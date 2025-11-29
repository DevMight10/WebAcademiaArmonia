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
            RoleSeeder::class,          // Primero los roles
            UserSeeder::class,          // Luego los usuarios
            ClienteSeeder::class,       // Clientes vinculados a usuarios
            BeneficiarioSeeder::class,  // Beneficiarios vinculados a clientes
            InstrumentoSeeder::class,   // Catálogo de instrumentos
        ]);

    }
}
