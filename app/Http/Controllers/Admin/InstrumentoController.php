<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instrumento;
use App\Enums\CategoriaInstrumento;
use App\Http\Requests\Admin\StoreInstrumentoRequest;
use App\Http\Requests\Admin\UpdateInstrumentoRequest;
use Illuminate\Http\Request;

class InstrumentoController extends Controller
{
    /**
     * Listar instrumentos con soporte para filtrado en tiempo real.
     * Retorna JSON cuando la petición es AJAX para actualización dinámica.
     */
    public function index(Request $request)
    {
        $query = Instrumento::query();

        // Aplicar búsqueda por nombre si está presente
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // Filtrar por categoría específica
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtrar por estado (activo/inactivo)
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado === '1');
        }

        // Ordenamiento alfabético para facilitar la búsqueda visual
        $instrumentos = $query->orderBy('nombre')->paginate(10)->withQueryString();

        $categorias = CategoriaInstrumento::cases();

        // Respuesta JSON para peticiones AJAX (búsqueda en tiempo real)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'instrumentos' => $instrumentos->items(),
                'pagination' => [
                    'current_page' => $instrumentos->currentPage(),
                    'last_page' => $instrumentos->lastPage(),
                    'per_page' => $instrumentos->perPage(),
                    'total' => $instrumentos->total(),
                ],
            ]);
        }

        return view('admin.instrumentos.index', compact('instrumentos', 'categorias'));
    }

    /**
     * Mostrar formulario de creación de instrumento.
     */
    public function create()
    {
        $categorias = CategoriaInstrumento::cases();
        return view('admin.instrumentos.create', compact('categorias'));
    }

    /**
     * Guardar nuevo instrumento en la base de datos.
     * El factor de costo se calcula automáticamente según la categoría.
     */
    public function store(StoreInstrumentoRequest $request)
    {
        // Obtener el enum de categoría para acceder al factor de costo
        $categoria = CategoriaInstrumento::from($request->categoria);

        $instrumento = Instrumento::create([
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'factor_costo' => $categoria->factorCosto(), // Factor automático según categoría
            'estado' => $request->has('estado') ? $request->estado : true,
        ]);

        return redirect()
            ->route('admin.instrumentos.index')
            ->with('success', 'Instrumento "' . $instrumento->nombre . '" creado exitosamente.');
    }

    /**
     * Mostrar detalles de un instrumento específico.
     */
    public function show(Instrumento $instrumento)
    {
        $categoria = CategoriaInstrumento::from($instrumento->categoria);
        return view('admin.instrumentos.show', compact('instrumento', 'categoria'));
    }

    /**
     * Mostrar formulario de edición de instrumento.
     */
    public function edit(Instrumento $instrumento)
    {
        $categorias = CategoriaInstrumento::cases();
        return view('admin.instrumentos.edit', compact('instrumento', 'categorias'));
    }

    /**
     * Actualizar instrumento existente.
     * Recalcula el factor de costo si cambió la categoría.
     */
    public function update(UpdateInstrumentoRequest $request, Instrumento $instrumento)
    {
        // Recalcular factor de costo en caso de cambio de categoría
        $categoria = CategoriaInstrumento::from($request->categoria);

        $instrumento->update([
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'factor_costo' => $categoria->factorCosto(),
            'estado' => $request->has('estado') ? $request->estado : false,
        ]);

        return redirect()
            ->route('admin.instrumentos.index')
            ->with('success', 'Instrumento "' . $instrumento->nombre . '" actualizado exitosamente.');
    }

    /**
     * Desactivar instrumento (soft delete).
     * No eliminamos físicamente para mantener historial de clases.
     */
    public function destroy(Instrumento $instrumento)
    {
        // Desactivación lógica en lugar de eliminación física
        $instrumento->update(['estado' => false]);

        return redirect()
            ->route('admin.instrumentos.index')
            ->with('success', 'Instrumento "' . $instrumento->nombre . '" desactivado exitosamente.');
    }

    /**
     * Reactivar un instrumento previamente desactivado.
     */
    public function restore(Instrumento $instrumento)
    {
        $instrumento->update(['estado' => true]);

        return redirect()
            ->route('admin.instrumentos.index')
            ->with('success', 'Instrumento "' . $instrumento->nombre . '" activado exitosamente.');
    }
}
