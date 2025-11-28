<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'Administrador',
                'slug' => 'administrador',
                'descripcion' => 'Acceso completo al sistema. Gestiona instrumentos, instructores, y genera reportes.'
            ],
            [
                'nombre' => 'Coordinador Académico',
                'slug' => 'coordinador',
                'descripcion' => 'Gestiona pagos, verifica compras, asigna instructores y administra el calendario.'
            ],
            [
                'nombre' => 'Instructor',
                'slug' => 'instructor',
                'descripcion' => 'Imparte clases musicales, registra inicio y finalización de sesiones.'
            ],
            [
                'nombre' => 'Cliente',
                'slug' => 'cliente',
                'descripcion' => 'Compra paquetes de créditos y registra beneficiarios.'
            ],
            [
                'nombre' => 'Beneficiario',
                'slug' => 'beneficiario',
                'descripcion' => 'Estudiante que consume créditos en clases musicales.'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
