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
        User::create([
            'name' => 'Administrador Sistema',
            'email' => 'admin@armonia.com',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
        ]);

        // Crear usuario coordinador de ejemplo
        $coordinadorRole = Role::where('slug', 'coordinador')->first();

        User::create([
            'name' => 'María Coordinadora',
            'email' => 'coordinador@armonia.com',
            'password' => Hash::make('coord123'),
            'role_id' => $coordinadorRole->id,
            'email_verified_at' => now(),
        ]);
    }
}
