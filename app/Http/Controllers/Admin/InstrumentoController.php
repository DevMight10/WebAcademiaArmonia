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
     * Display a listing of the resource.
     * Supports AJAX requests for live filtering (returns JSON).
     */
    public function index(Request $request)
    {
        $query = Instrumento::query();

        // ========================================
        // SEGURIDAD - PREVENCIÓN SQL INJECTION
        // Uso de Eloquent ORM con parameter binding
        // ========================================
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado === '1');
        }

        // Ordenar por nombre ascendente por defecto
        $instrumentos = $query->orderBy('nombre')->paginate(10)->withQueryString();

        // Obtener categorías para el filtro
        $categorias = CategoriaInstrumento::cases();

        // ========================================
        // RESPUESTA JSON PARA AJAX (BACKEND)
        // Detecta peticiones AJAX y retorna JSON
        // ========================================
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = CategoriaInstrumento::cases();
        return view('admin.instrumentos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstrumentoRequest $request)
    {
        // El factor_costo se asigna automáticamente según la categoría
        $categoria = CategoriaInstrumento::from($request->categoria);

        $instrumento = Instrumento::create([
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'factor_costo' => $categoria->factorCosto(),
            'estado' => $request->has('estado') ? $request->estado : true,
        ]);

        return redirect()
            ->route('admin.instrumentos.index')
            ->with('success', 'Instrumento "' . $instrumento->nombre . '" creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Instrumento $instrumento)
    {
        $categoria = CategoriaInstrumento::from($instrumento->categoria);
        return view('admin.instrumentos.show', compact('instrumento', 'categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instrumento $instrumento)
    {
        $categorias = CategoriaInstrumento::cases();
        return view('admin.instrumentos.edit', compact('instrumento', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstrumentoRequest $request, Instrumento $instrumento)
    {
        // El factor_costo se actualiza automáticamente según la categoría
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
     * Remove the specified resource from storage.
     */
    public function destroy(Instrumento $instrumento)
    {
        // No eliminamos, solo desactivamos
        $instrumento->update(['estado' => false]);

        return redirect()
            ->route('admin.instrumentos.index')
            ->with('success', 'Instrumento "' . $instrumento->nombre . '" desactivado exitosamente.');
    }

    /**
     * Restore a deactivated instrument.
     */
    public function restore(Instrumento $instrumento)
    {
        $instrumento->update(['estado' => true]);

        return redirect()
            ->route('admin.instrumentos.index')
            ->with('success', 'Instrumento "' . $instrumento->nombre . '" activado exitosamente.');
    }
}
