<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beneficiario;
use App\Models\User;
use Illuminate\Http\Request;

class BeneficiarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Beneficiario::with('user');

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            })->orWhere('ci', 'like', '%' . $search . '%');
        }

        $beneficiarios = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.beneficiarios.index', compact('beneficiarios'));
    }

    public function show(Beneficiario $beneficiario)
    {
        $beneficiario->load(['user', 'distribuciones.compra.cliente.user', 'citas.instructor.user', 'citas.instrumento']);
        
        // Estadísticas
        $totalCreditos = $beneficiario->distribuciones->sum('minutos_asignados');
        $creditosDisponibles = $beneficiario->distribuciones->sum('minutos_disponibles');
        $creditosConsumidos = $totalCreditos - $creditosDisponibles;
        $totalClases = $beneficiario->citas()->where('estado', 'completada')->count();
        
        return view('admin.beneficiarios.show', compact('beneficiario', 'totalCreditos', 'creditosDisponibles', 'creditosConsumidos', 'totalClases'));
    }

    public function destroy(Beneficiario $beneficiario)
    {
        // Verificar si tiene créditos o clases
        if ($beneficiario->distribuciones()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un beneficiario con créditos asignados.');
        }

        if ($beneficiario->citas()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un beneficiario con clases registradas.');
        }

        $userName = $beneficiario->user->name;
        $beneficiario->user->delete(); // Esto también eliminará el beneficiario por cascada
        
        return redirect()->route('admin.beneficiarios.index')
            ->with('success', "Beneficiario \"$userName\" eliminado exitosamente.");
    }
}
