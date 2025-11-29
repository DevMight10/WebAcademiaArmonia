<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Beneficiario;
use App\Models\Cliente;
use App\Models\Role;

class BeneficiarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el rol de beneficiario
        $beneficiarioRole = Role::where('slug', 'beneficiario')->first();

        // Obtener todos los clientes
        $clientes = Cliente::with('user')->get();

        // Cliente 1: Juan Pérez - 3 beneficiarios (él mismo + 2 hijos)
        if ($clientes->count() > 0) {
            $cliente1 = $clientes[0];

            // Beneficiario 1.1: El mismo cliente
            $user1_1 = User::firstOrCreate(
                ['email' => 'juan.perez.estudiante@gmail.com'],
                [
                    'name' => $cliente1->user->name,
                    'password' => Hash::make('beneficiario123'),
                    'role_id' => $beneficiarioRole->id,
                    'email_verified_at' => now(),
                ]
            );

            Beneficiario::firstOrCreate(
                ['user_id' => $user1_1->id],
                [
                    'ci' => $cliente1->ci,
                    'telefono' => $cliente1->telefono,
                ]
            );

            // Beneficiario 1.2: Hijo mayor - Luis Pérez
            $user1_2 = User::firstOrCreate(
                ['email' => 'luis.perez@gmail.com'],
                [
                    'name' => 'Luis Pérez García',
                    'password' => Hash::make('beneficiario123'),
                    'role_id' => $beneficiarioRole->id,
                    'email_verified_at' => now(),
                ]
            );

            Beneficiario::firstOrCreate(
                ['user_id' => $user1_2->id],
                [
                    'ci' => '1234567 LP',
                    'telefono' => '71234568',
                ]
            );

            // Beneficiario 1.3: Hija menor - Sofía Pérez
            $user1_3 = User::firstOrCreate(
                ['email' => 'sofia.perez@gmail.com'],
                [
                    'name' => 'Sofía Pérez García',
                    'password' => Hash::make('beneficiario123'),
                    'role_id' => $beneficiarioRole->id,
                    'email_verified_at' => now(),
                ]
            );

            Beneficiario::firstOrCreate(
                ['user_id' => $user1_3->id],
                [
                    'ci' => '2345678 LP',
                    'telefono' => '71234569',
                ]
            );
        }

        // Cliente 2: María García - 4 beneficiarios (máximo permitido)
        if ($clientes->count() > 1) {
            $cliente2 = $clientes[1];

            // Beneficiario 2.1: La misma cliente
            $user2_1 = User::firstOrCreate(
                ['email' => 'maria.garcia.estudiante@gmail.com'],
                [
                    'name' => $cliente2->user->name,
                    'password' => Hash::make('beneficiario123'),
                    'role_id' => $beneficiarioRole->id,
                    'email_verified_at' => now(),
                ]
            );

            Beneficiario::firstOrCreate(
                ['user_id' => $user2_1->id],
                [
                    'ci' => $cliente2->ci,
                    'telefono' => $cliente2->telefono,
                ]
            );

            // Beneficiario 2.2: Hijo - Diego García
            $user2_2 = User::firstOrCreate(
                ['email' => 'diego.garcia@gmail.com'],
                [
                    'name' => 'Diego García Mamani',
                    'password' => Hash::make('beneficiario123'),
                    'role_id' => $beneficiarioRole->id,
                    'email_verified_at' => now(),
                ]
            );

            Beneficiario::firstOrCreate(
                ['user_id' => $user2_2->id],
                [
                    'ci' => '3456789 LP',
                    'telefono' => '72345679',
                ]
            );

            // Beneficiario 2.3: Hija - Valentina García
            $user2_3 = User::firstOrCreate(
                ['email' => 'valentina.garcia@gmail.com'],
                [
                    'name' => 'Valentina García Mamani',
                    'password' => Hash::make('beneficiario123'),
                    'role_id' => $beneficiarioRole->id,
                    'email_verified_at' => now(),
                ]
            );

            Beneficiario::firstOrCreate(
                ['user_id' => $user2_3->id],
                [
                    'ci' => '4567890 LP',
                    'telefono' => '72345680',
                ]
            );

            // Beneficiario 2.4: Hijo menor - Mateo García
            $user2_4 = User::firstOrCreate(
                ['email' => 'mateo.garcia@gmail.com'],
                [
                    'name' => 'Mateo García Mamani',
                    'password' => Hash::make('beneficiario123'),
                    'role_id' => $beneficiarioRole->id,
                    'email_verified_at' => now(),
                ]
            );

            Beneficiario::firstOrCreate(
                ['user_id' => $user2_4->id],
                [
                    'ci' => '5678901 LP',
                    'telefono' => '72345681',
                ]
            );
        }

        // Cliente 3: Carlos Rodríguez - 1 beneficiario (solo él)
        if ($clientes->count() > 2) {
            $cliente3 = $clientes[2];

            $user3_1 = User::firstOrCreate(
                ['email' => 'carlos.rodriguez.estudiante@gmail.com'],
                [
                    'name' => $cliente3->user->name,
                    'password' => Hash::make('beneficiario123'),
                    'role_id' => $beneficiarioRole->id,
                    'email_verified_at' => now(),
                ]
            );

            Beneficiario::firstOrCreate(
                ['user_id' => $user3_1->id],
                [
                    'ci' => $cliente3->ci,
                    'telefono' => $cliente3->telefono,
                ]
            );
        }

        // Cliente 4: Ana Martínez - 2 beneficiarios (ella + 1 hijo)
        if ($clientes->count() > 3) {
            $cliente4 = $clientes[3];

            // Beneficiario 4.1: La misma cliente
            $user4_1 = User::firstOrCreate(
                ['email' => 'ana.martinez.estudiante@gmail.com'],
                [
                    'name' => $cliente4->user->name,
                    'password' => Hash::make('beneficiario123'),
                    'role_id' => $beneficiarioRole->id,
                    'email_verified_at' => now(),
                ]
            );

            Beneficiario::firstOrCreate(
                ['user_id' => $user4_1->id],
                [
                    'ci' => $cliente4->ci,
                    'telefono' => $cliente4->telefono,
                ]
            );

            // Beneficiario 4.2: Hija - Isabella Martínez
            $user4_2 = User::firstOrCreate(
                ['email' => 'isabella.martinez@gmail.com'],
                [
                    'name' => 'Isabella Martínez López',
                    'password' => Hash::make('beneficiario123'),
                    'role_id' => $beneficiarioRole->id,
                    'email_verified_at' => now(),
                ]
            );

            Beneficiario::firstOrCreate(
                ['user_id' => $user4_2->id],
                [
                    'ci' => '6789012 LP',
                    'telefono' => '74567891',
                ]
            );
        }

        // Cliente 5: Pedro Sánchez - Sin beneficiarios aún (cliente nuevo)
        // Esto permite probar el caso de un cliente sin beneficiarios
    }
}
