<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Instructor;
use App\Models\Instrumento;
use App\Enums\CategoriaInstructor;
use Illuminate\Support\Facades\Hash;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el rol de instructor
        $rolInstructor = Role::where('slug', 'instructor')->first();

        if (!$rolInstructor) {
            $this->command->error('El rol de instructor no existe. Ejecuta RoleSeeder primero.');
            return;
        }

        // Obtener instrumentos para asignar especialidades
        $instrumentos = Instrumento::all();

        if ($instrumentos->count() === 0) {
            $this->command->error('No hay instrumentos en la BD. Ejecuta InstrumentoSeeder primero.');
            return;
        }

        // Datos de instructores
        $instructores = [
            // INSTRUCTORES REGULARES
            [
                'nombre' => 'Carlos',
                'apellido' => 'Rodríguez',
                'ci' => '7654321',
                'telefono' => '70123456',
                'email' => 'carlos.rodriguez@academiaarmonia.com',
                'categoria' => CategoriaInstructor::REGULAR,
                'especialidades' => ['Guitarra Acústica', 'Guitarra Eléctrica', 'Bajo Eléctrico'],
                'estado' => true,
            ],
            [
                'nombre' => 'María',
                'apellido' => 'Fernández',
                'ci' => '8765432',
                'telefono' => '71234567',
                'email' => 'maria.fernandez@academiaarmonia.com',
                'categoria' => CategoriaInstructor::REGULAR,
                'especialidades' => ['Piano', 'Teclado'],
                'estado' => true,
            ],
            [
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'ci' => '9876543',
                'telefono' => '72345678',
                'email' => 'juan.perez@academiaarmonia.com',
                'categoria' => CategoriaInstructor::REGULAR,
                'especialidades' => ['Batería', 'Percusión'],
                'estado' => true,
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'López',
                'ci' => '6543210',
                'telefono' => '73456789',
                'email' => 'ana.lopez@academiaarmonia.com',
                'categoria' => CategoriaInstructor::REGULAR,
                'especialidades' => ['Flauta Traversa', 'Clarinete'],
                'estado' => true,
            ],
            [
                'nombre' => 'Pedro',
                'apellido' => 'Sánchez',
                'ci' => '5432109',
                'telefono' => '74567890',
                'email' => 'pedro.sanchez@academiaarmonia.com',
                'categoria' => CategoriaInstructor::REGULAR,
                'especialidades' => ['Ukelele', 'Guitarra Acústica'],
                'estado' => true,
            ],

            // INSTRUCTORES PREMIUM
            [
                'nombre' => 'Laura',
                'apellido' => 'Martínez',
                'ci' => '4321098',
                'telefono' => '75678901',
                'email' => 'laura.martinez@academiaarmonia.com',
                'categoria' => CategoriaInstructor::PREMIUM,
                'especialidades' => ['Violín', 'Violonchelo'],
                'estado' => true,
            ],
            [
                'nombre' => 'Roberto',
                'apellido' => 'García',
                'ci' => '3210987',
                'telefono' => '76789012',
                'email' => 'roberto.garcia@academiaarmonia.com',
                'categoria' => CategoriaInstructor::PREMIUM,
                'especialidades' => ['Piano', 'Canto Lírico'],
                'estado' => true,
            ],
            [
                'nombre' => 'Sofía',
                'apellido' => 'Ramírez',
                'ci' => '2109876',
                'telefono' => '77890123',
                'email' => 'sofia.ramirez@academiaarmonia.com',
                'categoria' => CategoriaInstructor::PREMIUM,
                'especialidades' => ['Saxofón', 'Clarinete', 'Oboe'],
                'estado' => true,
            ],
            [
                'nombre' => 'Diego',
                'apellido' => 'Torres',
                'ci' => '1098765',
                'telefono' => '78901234',
                'email' => 'diego.torres@academiaarmonia.com',
                'categoria' => CategoriaInstructor::PREMIUM,
                'especialidades' => ['Trompeta', 'Trombón'],
                'estado' => true,
            ],

            // INSTRUCTORES INVITADOS
            [
                'nombre' => 'Isabella',
                'apellido' => 'Morales',
                'ci' => '1234567',
                'telefono' => '79012345',
                'email' => 'isabella.morales@academiaarmonia.com',
                'categoria' => CategoriaInstructor::INVITADO,
                'especialidades' => ['Arpa', 'Piano'],
                'estado' => true,
            ],
            [
                'nombre' => 'Fernando',
                'apellido' => 'Vega',
                'ci' => '2345678',
                'telefono' => '70987654',
                'email' => 'fernando.vega@academiaarmonia.com',
                'categoria' => CategoriaInstructor::INVITADO,
                'especialidades' => ['Contrabajo', 'Violonchelo'],
                'estado' => true,
            ],
            [
                'nombre' => 'Valentina',
                'apellido' => 'Castro',
                'ci' => '3456789',
                'telefono' => '71987654',
                'email' => 'valentina.castro@academiaarmonia.com',
                'categoria' => CategoriaInstructor::INVITADO,
                'especialidades' => ['Canto Lírico'],
                'estado' => true,
            ],

            // INSTRUCTOR INACTIVO (para probar filtros)
            [
                'nombre' => 'Miguel',
                'apellido' => 'Herrera',
                'ci' => '4567890',
                'telefono' => '72987654',
                'email' => 'miguel.herrera@academiaarmonia.com',
                'categoria' => CategoriaInstructor::REGULAR,
                'especialidades' => ['Batería'],
                'estado' => false,
            ],
        ];

        // Crear instructores
        foreach ($instructores as $instructorData) {
            // Crear usuario
            $user = User::create([
                'name' => $instructorData['nombre'] . ' ' . $instructorData['apellido'],
                'email' => $instructorData['email'],
                'password' => Hash::make('password123'),
                'role_id' => $rolInstructor->id,
                'email_verified_at' => now(),
            ]);

            // Crear instructor
            $instructor = Instructor::create([
                'user_id' => $user->id,
                'ci' => $instructorData['ci'],
                'telefono' => $instructorData['telefono'],
                'categoria' => $instructorData['categoria']->value,
                'factor_costo' => $instructorData['categoria']->factorCosto(),
                'estado' => $instructorData['estado'],
            ]);

            // Asignar especialidades
            foreach ($instructorData['especialidades'] as $nombreInstrumento) {
                $instrumento = $instrumentos->where('nombre', $nombreInstrumento)->first();

                if ($instrumento) {
                    $instructor->especialidades()->create([
                        'instrumento_id' => $instrumento->id,
                    ]);
                }
            }

            $this->command->info("Instructor creado: {$user->name} ({$instructorData['categoria']->label()})");
        }

        $this->command->info("\n✓ Se crearon " . count($instructores) . " instructores exitosamente.");
    }
}
