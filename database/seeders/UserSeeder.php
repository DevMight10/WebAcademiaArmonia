<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el rol de administrador
        $adminRole = Role::where('slug', 'administrador')->first();

        // Crear usuario administrador por defecto
        User::firstOrCreate(
            ['email' => 'admin@armonia.com'],
            [
                'name' => 'Administrador Sistema',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario coordinador de ejemplo
        $coordinadorRole = Role::where('slug', 'coordinador')->first();

        User::firstOrCreate(
            ['email' => 'coordinador@armonia.com'],
            [
                'name' => 'María Coordinadora',
                'password' => Hash::make('coord123'),
                'role_id' => $coordinadorRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
