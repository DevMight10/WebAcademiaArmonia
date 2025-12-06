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
            ['email' => 'admin@academiaarmonia.com'],
            [
                'name' => 'Administrador Sistema',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario coordinador
        $coordinadorRole = Role::where('slug', 'coordinador')->first();

        User::firstOrCreate(
            ['email' => 'coordinador@academiaarmonia.com'],
            [
                'name' => 'Coordinador Académico',
                'password' => Hash::make('password123'),
                'role_id' => $coordinadorRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
