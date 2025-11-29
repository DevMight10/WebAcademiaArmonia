<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Role;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el rol de cliente
        $clienteRole = Role::where('slug', 'cliente')->first();

        // Cliente 1: Juan Pérez (cliente principal para pruebas)
        $user1 = User::firstOrCreate(
            ['email' => 'juan.perez@gmail.com'],
            [
                'name' => 'Juan Pérez Mamani',
                'password' => Hash::make('cliente123'),
                'role_id' => $clienteRole->id,
                'email_verified_at' => now(),
            ]
        );

        Cliente::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'ci' => '7654321 LP',
                'telefono' => '71234567',
            ]
        );

        // Cliente 2: María García (cliente con familia)
        $user2 = User::firstOrCreate(
            ['email' => 'maria.garcia@gmail.com'],
            [
                'name' => 'María García Quispe',
                'password' => Hash::make('cliente123'),
                'role_id' => $clienteRole->id,
                'email_verified_at' => now(),
            ]
        );

        Cliente::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'ci' => '8765432 LP',
                'telefono' => '72345678',
            ]
        );

        // Cliente 3: Carlos Rodríguez (cliente individual)
        $user3 = User::firstOrCreate(
            ['email' => 'carlos.rodriguez@gmail.com'],
            [
                'name' => 'Carlos Rodríguez Flores',
                'password' => Hash::make('cliente123'),
                'role_id' => $clienteRole->id,
                'email_verified_at' => now(),
            ]
        );

        Cliente::firstOrCreate(
            ['user_id' => $user3->id],
            [
                'ci' => '9876543 LP',
                'telefono' => '73456789',
            ]
        );

        // Cliente 4: Ana Martínez (cliente con hijos)
        $user4 = User::firstOrCreate(
            ['email' => 'ana.martinez@gmail.com'],
            [
                'name' => 'Ana Martínez Condori',
                'password' => Hash::make('cliente123'),
                'role_id' => $clienteRole->id,
                'email_verified_at' => now(),
            ]
        );

        Cliente::firstOrCreate(
            ['user_id' => $user4->id],
            [
                'ci' => '6543210 LP',
                'telefono' => '74567890',
            ]
        );

        // Cliente 5: Pedro Sánchez (cliente nuevo)
        $user5 = User::firstOrCreate(
            ['email' => 'pedro.sanchez@gmail.com'],
            [
                'name' => 'Pedro Sánchez Ticona',
                'password' => Hash::make('cliente123'),
                'role_id' => $clienteRole->id,
                'email_verified_at' => now(),
            ]
        );

        Cliente::firstOrCreate(
            ['user_id' => $user5->id],
            [
                'ci' => '5432109 LP',
                'telefono' => '75678901',
            ]
        );
    }
}
