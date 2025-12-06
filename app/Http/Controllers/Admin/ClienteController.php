<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::with('user');

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            })->orWhere('ci', 'like', '%' . $search . '%');
        }

        $clientes = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.clientes.index', compact('clientes'));
    }

    public function show(Cliente $cliente)
    {
        $cliente->load(['user', 'compras.distribuciones.beneficiario.user']);
        
        // Estadísticas
        $totalCompras = $cliente->compras->count();
        $totalInvertido = $cliente->compras->sum('total');
        $minutosComprados = $cliente->compras->sum('minutos_totales');
        
        return view('admin.clientes.show', compact('cliente', 'totalCompras', 'totalInvertido', 'minutosComprados'));
    }

    public function destroy(Cliente $cliente)
    {
        // Verificar si tiene compras
        if ($cliente->compras()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un cliente con compras registradas.');
        }

        $userName = $cliente->user->name;
        $cliente->user->delete(); // Esto también eliminará el cliente por cascada
        
        return redirect()->route('admin.clientes.index')
            ->with('success', "Cliente \"$userName\" eliminado exitosamente.");
    }
}
