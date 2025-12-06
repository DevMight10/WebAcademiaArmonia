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

        return view('admin.instructores.index', compact('instructores', 'categorias', 'instrumentos'));
    }

    /**
     * Mostrar formulario de creación de instructor.
     */
    public function create()
    {
        $categorias = CategoriaInstructor::cases();
        $instrumentos = Instrumento::where('estado', true)->orderBy('nombre')->get();

        return view('admin.instructores.create', compact('categorias', 'instrumentos'));
    }

    /**
     * Guardar nuevo instructor con usuario asociado.
     * Crea automáticamente un usuario con contraseña temporal.
     */
    public function store(StoreInstructorRequest $request)
    {
        try {
            DB::beginTransaction();

            // Verificar que el rol de instructor existe en la BD
            $rolInstructor = Role::where('slug', 'instructor')->first();

            if (!$rolInstructor) {
                throw new \Exception('El rol de instructor no existe en el sistema.');
            }

            // Crear usuario del sistema para el instructor
            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->email,
                'password' => Hash::make('password123'), // Temporal - debe cambiarla en primer login
                'role_id' => $rolInstructor->id,
                'email_verified_at' => now(),
            ]);

            // Factor de costo automático según categoría (Regular/Premium/Invitado)
            $categoria = CategoriaInstructor::from($request->categoria);

            // Crear perfil de instructor vinculado al usuario
            $instructor = Instructor::create([
                'user_id' => $user->id,
                'ci' => $request->ci,
                'telefono' => $request->telefono,
                'categoria' => $request->categoria,
                'factor_costo' => $categoria->factorCosto(),
                'estado' => $request->has('estado') ? $request->estado : true,
            ]);

            // Asignar especialidades (instrumentos que puede enseñar)
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
     * Mostrar detalles completos de un instructor.
     */
    public function show(Instructor $instructore)
    {
        $instructore->load(['user', 'especialidades.instrumento', 'horarios']);
        $categoria = CategoriaInstructor::from($instructore->categoria);

        return view('admin.instructores.show', compact('instructore', 'categoria'));
    }

    /**
     * Mostrar formulario de edición de instructor.
     */
    public function edit(Instructor $instructore)
    {
        $instructore->load(['user', 'especialidades']);
        $categorias = CategoriaInstructor::cases();
        $instrumentos = Instrumento::where('estado', true)->orderBy('nombre')->get();

        // Pre-seleccionar especialidades actuales en el formulario
        $especialidadesActuales = $instructore->especialidades->pluck('instrumento_id')->toArray();

        return view('admin.instructores.edit', compact('instructore', 'categorias', 'instrumentos', 'especialidadesActuales'));
    }

    /**
     * Actualizar instructor y sus especialidades.
     * Recalcula el factor de costo si cambió la categoría.
     */
    public function update(UpdateInstructorRequest $request, Instructor $instructore)
    {
        try {
            DB::beginTransaction();

            // Actualizar nombre y email en la tabla users
            $instructore->user->update([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->email,
            ]);

            // Recalcular factor de costo si cambió de categoría
            $categoria = CategoriaInstructor::from($request->categoria);

            // Actualizar datos del perfil de instructor
            $instructore->update([
                'ci' => $request->ci,
                'telefono' => $request->telefono,
                'categoria' => $request->categoria,
                'factor_costo' => $categoria->factorCosto(),
                'estado' => $request->has('estado') ? $request->estado : false,
            ]);

            // Reemplazar especialidades (eliminar antiguas y crear nuevas)
            $instructore->especialidades()->delete();

            // Asignar nuevas especialidades seleccionadas
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
     * Desactivar instructor (soft delete).
     * No eliminamos para mantener historial de clases impartidas.
     */
    public function destroy(Instructor $instructore)
    {
        // Desactivación lógica para preservar historial
        $instructore->update(['estado' => false]);

        return redirect()
            ->route('admin.instructores.index')
            ->with('success', 'Instructor "' . $instructore->user->name . '" desactivado exitosamente.');
    }

    /**
     * Reactivar un instructor previamente desactivado.
     */
    public function restore(Instructor $instructore)
    {
        $instructore->update(['estado' => true]);

        return redirect()
            ->route('admin.instructores.index')
            ->with('success', 'Instructor "' . $instructore->user->name . '" activado exitosamente.');
    }
}
