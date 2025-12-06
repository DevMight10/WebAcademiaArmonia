<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Cita;
use App\Models\Cliente;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas de Compras
        $comprasPendientes = Compra::where('estado', 'pendiente')->count();
        $comprasAprobadas = Compra::where('estado', 'aprobada')->count();
        $comprasRechazadas = Compra::where('estado', 'rechazada')->count();
        
        // Estadísticas de Citas
        $citasPendientes = Cita::where('estado', 'pendiente')->count();
        $citasConfirmadas = Cita::where('estado', 'confirmada')->count();
        $citasCompletadas = Cita::where('estado', 'completada')->count();
        
        // Ingresos del mes
        $ingresosDelMes = Compra::where('estado', 'aprobada')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        
        // Estudiantes activos (clientes con compras aprobadas)
        $estudiantesActivos = Cliente::whereHas('compras', function($q) {
            $q->where('estado', 'aprobada');
        })->count();
        
        // Citas próximas (próximas 5 citas futuras)
        $citasProximas = Cita::with(['beneficiario.user', 'instructor.user', 'instrumento'])
            ->where('estado', 'confirmada')
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora')
            ->limit(5)
            ->get();
        
        // Compras pendientes recientes
        $comprasPendientesRecientes = Compra::with('cliente.user')
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('coordinador.dashboard', compact(
            'comprasPendientes',
            'comprasAprobadas',
            'comprasRechazadas',
            'citasPendientes',
            'citasConfirmadas',
            'citasCompletadas',
            'ingresosDelMes',
            'estudiantesActivos',
            'citasProximas',
            'comprasPendientesRecientes'
        ));
    }
}
