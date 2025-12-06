<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Instructor;
use App\Models\Instrumento;
use App\Models\InstructorEspecialidad;
use App\Enums\CategoriaInstructor;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructorRole = Role::where('slug', 'instructor')->first();

        // Instructor 1: Carlos - Piano y Guitarra (Regular)
        $user1 = User::firstOrCreate(
            ['email' => 'carlos_instructor@academiaarmonia.com'],
            [
                'name' => 'Carlos Rodríguez',
                'password' => Hash::make('password123'),
                'role_id' => $instructorRole->id,
                'email_verified_at' => now(),
            ]
        );

        $instructor1 = Instructor::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'ci' => '12345678SC',
                'telefono' => '71234567',
                'categoria' => CategoriaInstructor::REGULAR->value,
                'factor_costo' => CategoriaInstructor::REGULAR->factorCosto(),
                'estado' => true,
            ]
        );

        // Especialidades: Piano y Guitarra
        $piano = Instrumento::where('nombre', 'Piano')->first();
        $guitarra = Instrumento::where('nombre', 'LIKE', '%Guitarra%')->first();
        
        if ($piano) {
            InstructorEspecialidad::firstOrCreate([
                'instructor_id' => $instructor1->id,
                'instrumento_id' => $piano->id,
            ]);
        }
        
        if ($guitarra) {
            InstructorEspecialidad::firstOrCreate([
                'instructor_id' => $instructor1->id,
                'instrumento_id' => $guitarra->id,
            ]);
        }

        // Instructor 2: María - Violín y Saxofón (Premium)
        $user2 = User::firstOrCreate(
            ['email' => 'maria_instructor@academiaarmonia.com'],
            [
                'name' => 'María López',
                'password' => Hash::make('password123'),
                'role_id' => $instructorRole->id,
                'email_verified_at' => now(),
            ]
        );

        $instructor2 = Instructor::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'ci' => '87654321LP',
                'telefono' => '72345678',
                'categoria' => CategoriaInstructor::PREMIUM->value,
                'factor_costo' => CategoriaInstructor::PREMIUM->factorCosto(),
                'estado' => true,
            ]
        );

        // Especialidades: Violín y Saxofón
        $violin = Instrumento::where('nombre', 'Violín')->first();
        $saxofon = Instrumento::where('nombre', 'Saxofón')->first();
        
        if ($violin) {
            InstructorEspecialidad::firstOrCreate([
                'instructor_id' => $instructor2->id,
                'instrumento_id' => $violin->id,
            ]);
        }
        
        if ($saxofon) {
            InstructorEspecialidad::firstOrCreate([
                'instructor_id' => $instructor2->id,
                'instrumento_id' => $saxofon->id,
            ]);
        }

        // Instructor 3: Juan - Batería (Regular)
        $user3 = User::firstOrCreate(
            ['email' => 'juan_instructor@academiaarmonia.com'],
            [
                'name' => 'Juan Pérez',
                'password' => Hash::make('password123'),
                'role_id' => $instructorRole->id,
                'email_verified_at' => now(),
            ]
        );

        $instructor3 = Instructor::firstOrCreate(
            ['user_id' => $user3->id],
            [
                'ci' => '45678912CB',
                'telefono' => '73456789',
                'categoria' => CategoriaInstructor::REGULAR->value,
                'factor_costo' => CategoriaInstructor::REGULAR->factorCosto(),
                'estado' => true,
            ]
        );

        // Especialidad: Batería
        $bateria = Instrumento::where('nombre', 'Batería')->first();
        
        if ($bateria) {
            InstructorEspecialidad::firstOrCreate([
                'instructor_id' => $instructor3->id,
                'instrumento_id' => $bateria->id,
            ]);
        }
    }
}
