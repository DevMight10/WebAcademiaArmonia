<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Beneficiario;

class BeneficiarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beneficiarioRole = Role::where('slug', 'beneficiario')->first();

        // Beneficiario 1: Sofía García
        $user1 = User::firstOrCreate(
            ['email' => 'sofia_beneficiario@academiaarmonia.com'],
            [
                'name' => 'Sofía García',
                'password' => Hash::make('password123'),
                'role_id' => $beneficiarioRole->id,
                'email_verified_at' => now(),
            ]
        );

        Beneficiario::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'ci' => '55667788BE',
                'telefono' => '77890123',
            ]
        );

        // Beneficiario 2: Diego Martínez
        $user2 = User::firstOrCreate(
            ['email' => 'diego_beneficiario@academiaarmonia.com'],
            [
                'name' => 'Diego Martínez',
                'password' => Hash::make('password123'),
                'role_id' => $beneficiarioRole->id,
                'email_verified_at' => now(),
            ]
        );

        Beneficiario::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'ci' => '66778899PD',
                'telefono' => '78901234',
            ]
        );

        // Beneficiario 3: Valentina Sánchez
        $user3 = User::firstOrCreate(
            ['email' => 'valentina_beneficiario@academiaarmonia.com'],
            [
                'name' => 'Valentina Sánchez',
                'password' => Hash::make('password123'),
                'role_id' => $beneficiarioRole->id,
                'email_verified_at' => now(),
            ]
        );

        Beneficiario::firstOrCreate(
            ['user_id' => $user3->id],
            [
                'ci' => '77889900CH',
                'telefono' => '79012345',
            ]
        );

        // Beneficiario 4: Mateo López
        $user4 = User::firstOrCreate(
            ['email' => 'mateo_beneficiario@academiaarmonia.com'],
            [
                'name' => 'Mateo López',
                'password' => Hash::make('password123'),
                'role_id' => $beneficiarioRole->id,
                'email_verified_at' => now(),
            ]
        );

        Beneficiario::firstOrCreate(
            ['user_id' => $user4->id],
            [
                'ci' => '88990011SC',
                'telefono' => '70123456',
            ]
        );
    }
}
