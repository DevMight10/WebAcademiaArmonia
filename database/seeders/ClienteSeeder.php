<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clienteRole = Role::where('slug', 'cliente')->first();

        // Cliente 1: Ana García
        $user1 = User::firstOrCreate(
            ['email' => 'ana_cliente@academiaarmonia.com'],
            [
                'name' => 'Ana García',
                'password' => Hash::make('password123'),
                'role_id' => $clienteRole->id,
                'email_verified_at' => now(),
            ]
        );

        Cliente::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'ci' => '11223344OR',
                'telefono' => '74567890',
            ]
        );

        // Cliente 2: Pedro Martínez
        $user2 = User::firstOrCreate(
            ['email' => 'pedro_cliente@academiaarmonia.com'],
            [
                'name' => 'Pedro Martínez',
                'password' => Hash::make('password123'),
                'role_id' => $clienteRole->id,
                'email_verified_at' => now(),
            ]
        );

        Cliente::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'ci' => '22334455PT',
                'telefono' => '75678901',
            ]
        );

        // Cliente 3: Laura Sánchez
        $user3 = User::firstOrCreate(
            ['email' => 'laura_cliente@academiaarmonia.com'],
            [
                'name' => 'Laura Sánchez',
                'password' => Hash::make('password123'),
                'role_id' => $clienteRole->id,
                'email_verified_at' => now(),
            ]
        );

        Cliente::firstOrCreate(
            ['user_id' => $user3->id],
            [
                'ci' => '33445566TJ',
                'telefono' => '76789012',
            ]
        );
    }
}
