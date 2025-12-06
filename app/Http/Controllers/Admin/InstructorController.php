<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\User;
use App\Models\Role;
use App\Models\Instrumento;
use App\Enums\CategoriaInstructor;
use App\Http\Requests\Admin\StoreInstructorRequest;
use App\Http\Requests\Admin\UpdateInstructorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstructorController extends Controller
{
    /**
     * Listar instructores con filtros múltiples.
     * Incluye búsqueda por nombre, CI, categoría, estado y especialidad.
     */
    public function index(Request $request)
    {
        $query = Instructor::with(['user', 'especialidades.instrumento']);

        // Búsqueda por nombre o CI
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhere('ci', 'like', '%' . $search . '%');
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado === '1');
        }

        // Filtro por instrumento/especialidad
        if ($request->filled('instrumento')) {
            $query->whereHas('especialidades', function($q) use ($request) {
                $q->where('instrumento_id', $request->instrumento);
            });
        }

        // Ordenar por nombre del usuario
        $instructores = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Obtener categorías para el filtro
        $categorias = CategoriaInstructor::cases();

        // Obtener instrumentos para filtro
        $instrumentos = Instrumento::where('estado', true)->orderBy('nombre')->get();

        // Respuesta JSON para peticiones AJAX (búsqueda en tiempo real)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'instructores' => $instructores->items(),
                'pagination' => [
                    'current_page' => $instructores->currentPage(),
                    'last_page' => $instructores->lastPage(),
                    'per_page' => $instructores->perPage(),
                    'total' => $instructores->total(),
                ],
            ]);
        }

        return view('admin.instructores.index', compact('instructores', 'categorias', 'instrumentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = CategoriaInstructor::cases();
        $instrumentos = Instrumento::where('estado', true)->orderBy('nombre')->get();

        return view('admin.instructores.create', compact('categorias', 'instrumentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstructorRequest $request)
    {
        try {
            DB::beginTransaction();

            // Obtener el rol de instructor
            $rolInstructor = Role::where('slug', 'instructor')->first();

            if (!$rolInstructor) {
                throw new \Exception('El rol de instructor no existe en el sistema.');
            }

            // Crear el usuario asociado
            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->email,
                'password' => Hash::make('password123'), // Contraseña temporal
                'role_id' => $rolInstructor->id,
                'email_verified_at' => now(),
            ]);

            // Obtener el factor de costo según la categoría
            $categoria = CategoriaInstructor::from($request->categoria);

            // Crear el instructor
            $instructor = Instructor::create([
                'user_id' => $user->id,
                'ci' => $request->ci,
                'telefono' => $request->telefono,
                'categoria' => $request->categoria,
                'factor_costo' => $categoria->factorCosto(),
                'estado' => $request->has('estado') ? $request->estado : true,
            ]);

            // Sincronizar especialidades (instrumentos que puede enseñar)
            if ($request->has('especialidades') && is_array($request->especialidades)) {
                foreach ($request->especialidades as $instrumentoId) {
                    $instructor->especialidades()->create([
                        'instrumento_id' => $instrumentoId,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.instructores.index')
                ->with('success', 'Instructor "' . $user->name . '" creado exitosamente. Contraseña temporal: password123');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear el instructor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructore)
    {
        $instructore->load(['user', 'especialidades.instrumento', 'horarios']);
        $categoria = CategoriaInstructor::from($instructore->categoria);

        return view('admin.instructores.show', compact('instructore', 'categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instructor $instructore)
    {
        $instructore->load(['user', 'especialidades']);
        $categorias = CategoriaInstructor::cases();
        $instrumentos = Instrumento::where('estado', true)->orderBy('nombre')->get();

        // Obtener IDs de especialidades actuales
        $especialidadesActuales = $instructore->especialidades->pluck('instrumento_id')->toArray();

        return view('admin.instructores.edit', compact('instructore', 'categorias', 'instrumentos', 'especialidadesActuales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorRequest $request, Instructor $instructore)
    {
        try {
            DB::beginTransaction();

            // Actualizar datos del usuario
            $instructore->user->update([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->email,
            ]);

            // Actualizar el factor de costo según la categoría
            $categoria = CategoriaInstructor::from($request->categoria);

            // Actualizar el instructor
            $instructore->update([
                'ci' => $request->ci,
                'telefono' => $request->telefono,
                'categoria' => $request->categoria,
                'factor_costo' => $categoria->factorCosto(),
                'estado' => $request->has('estado') ? $request->estado : false,
            ]);

            // Actualizar especialidades
            // Eliminar las especialidades actuales
            $instructore->especialidades()->delete();

            // Crear las nuevas especialidades
            if ($request->has('especialidades') && is_array($request->especialidades)) {
                foreach ($request->especialidades as $instrumentoId) {
                    $instructore->especialidades()->create([
                        'instrumento_id' => $instrumentoId,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.instructores.index')
                ->with('success', 'Instructor "' . $instructore->user->name . '" actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar el instructor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructore)
    {
        // No eliminamos, solo desactivamos
        $instructore->update(['estado' => false]);

        return redirect()
            ->route('admin.instructores.index')
            ->with('success', 'Instructor "' . $instructore->user->name . '" desactivado exitosamente.');
    }

    /**
     * Restore a deactivated instructor.
     */
    public function restore(Instructor $instructore)
    {
        $instructore->update(['estado' => true]);

        return redirect()
            ->route('admin.instructores.index')
            ->with('success', 'Instructor "' . $instructore->user->name . '" activado exitosamente.');
    }
}
