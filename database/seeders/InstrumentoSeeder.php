<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Instrumento;
use App\Enums\CategoriaInstrumento;

class InstrumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instrumentos = [
            // BÁSICOS (Factor: 1.0)
            [
                'nombre' => 'Guitarra Acústica',
                'categoria' => CategoriaInstrumento::BASICO->value,
                'factor_costo' => CategoriaInstrumento::BASICO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Guitarra Eléctrica',
                'categoria' => CategoriaInstrumento::BASICO->value,
                'factor_costo' => CategoriaInstrumento::BASICO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Ukelele',
                'categoria' => CategoriaInstrumento::BASICO->value,
                'factor_costo' => CategoriaInstrumento::BASICO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Bajo Eléctrico',
                'categoria' => CategoriaInstrumento::BASICO->value,
                'factor_costo' => CategoriaInstrumento::BASICO->factorCosto(),
                'estado' => true,
            ],

            // INTERMEDIOS (Factor: 1.0)
            [
                'nombre' => 'Piano',
                'categoria' => CategoriaInstrumento::INTERMEDIO->value,
                'factor_costo' => CategoriaInstrumento::INTERMEDIO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Violín',
                'categoria' => CategoriaInstrumento::INTERMEDIO->value,
                'factor_costo' => CategoriaInstrumento::INTERMEDIO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Flauta Traversa',
                'categoria' => CategoriaInstrumento::INTERMEDIO->value,
                'factor_costo' => CategoriaInstrumento::INTERMEDIO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Clarinete',
                'categoria' => CategoriaInstrumento::INTERMEDIO->value,
                'factor_costo' => CategoriaInstrumento::INTERMEDIO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Teclado',
                'categoria' => CategoriaInstrumento::INTERMEDIO->value,
                'factor_costo' => CategoriaInstrumento::INTERMEDIO->factorCosto(),
                'estado' => true,
            ],

            // AVANZADOS (Factor: 1.15)
            [
                'nombre' => 'Saxofón',
                'categoria' => CategoriaInstrumento::AVANZADO->value,
                'factor_costo' => CategoriaInstrumento::AVANZADO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Batería',
                'categoria' => CategoriaInstrumento::AVANZADO->value,
                'factor_costo' => CategoriaInstrumento::AVANZADO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Canto Lírico',
                'categoria' => CategoriaInstrumento::AVANZADO->value,
                'factor_costo' => CategoriaInstrumento::AVANZADO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Oboe',
                'categoria' => CategoriaInstrumento::AVANZADO->value,
                'factor_costo' => CategoriaInstrumento::AVANZADO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Percusión',
                'categoria' => CategoriaInstrumento::AVANZADO->value,
                'factor_costo' => CategoriaInstrumento::AVANZADO->factorCosto(),
                'estado' => true,
            ],

            // ESPECIALIZADOS (Factor: 1.25)
            [
                'nombre' => 'Arpa',
                'categoria' => CategoriaInstrumento::ESPECIALIZADO->value,
                'factor_costo' => CategoriaInstrumento::ESPECIALIZADO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Violonchelo',
                'categoria' => CategoriaInstrumento::ESPECIALIZADO->value,
                'factor_costo' => CategoriaInstrumento::ESPECIALIZADO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Trompeta',
                'categoria' => CategoriaInstrumento::ESPECIALIZADO->value,
                'factor_costo' => CategoriaInstrumento::ESPECIALIZADO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Trombón',
                'categoria' => CategoriaInstrumento::ESPECIALIZADO->value,
                'factor_costo' => CategoriaInstrumento::ESPECIALIZADO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Fagot',
                'categoria' => CategoriaInstrumento::ESPECIALIZADO->value,
                'factor_costo' => CategoriaInstrumento::ESPECIALIZADO->factorCosto(),
                'estado' => true,
            ],
            [
                'nombre' => 'Contrabajo',
                'categoria' => CategoriaInstrumento::ESPECIALIZADO->value,
                'factor_costo' => CategoriaInstrumento::ESPECIALIZADO->factorCosto(),
                'estado' => true,
            ],
        ];

        foreach ($instrumentos as $instrumento) {
            Instrumento::create($instrumento);
        }
    }
}
