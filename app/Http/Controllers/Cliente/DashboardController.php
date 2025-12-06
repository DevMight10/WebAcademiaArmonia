<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Cita;
use App\Models\DistribucionCredito;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $cliente = auth()->user()->cliente;
        
        // Estadísticas generales
        $totalCompras = Compra::where('cliente_id', $cliente->id)->count();
        // Total gastado incluye todas las compras (no requiere aprobación para "gastar")
        $totalGastado = Compra::where('cliente_id', $cliente->id)->sum('total');
        
        // Créditos totales y disponibles
        $distribuciones = DistribucionCredito::whereHas('compra', function($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->get();
        
        $totalMinutosComprados = $distribuciones->sum('minutos_asignados');
        $totalMinutosDisponibles = $distribuciones->sum('minutos_disponibles');
        $totalMinutosConsumidos = $totalMinutosComprados - $totalMinutosDisponibles;
        
        // Beneficiarios activos
        $beneficiariosActivos = $distribuciones->where('minutos_disponibles', '>', 0)
            ->unique('beneficiario_id')
            ->count();
        
        // Próximas clases de todos los beneficiarios
        $beneficiariosIds = $distribuciones->pluck('beneficiario_id')->unique();
        $proximasClases = Cita::whereIn('beneficiario_id', $beneficiariosIds)
            ->where('fecha_hora', '>', now())
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->with(['beneficiario.user', 'instructor.user', 'instrumento'])
            ->orderBy('fecha_hora', 'asc')
            ->limit(5)
            ->get();
        
        // Progreso por beneficiario
        $progresoBeneficiarios = DistribucionCredito::whereHas('compra', function($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })
        ->with('beneficiario.user')
        ->select('beneficiario_id', 
            DB::raw('SUM(minutos_asignados) as total'),
            DB::raw('SUM(minutos_disponibles) as disponibles'))
        ->groupBy('beneficiario_id')
        ->get()
        ->map(function($item) {
            $item->consumidos = $item->total - $item->disponibles;
            return $item;
        });
        
        // Consumo mensual (últimos 6 meses)
        $consumoMensual = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $minutos = Cita::whereIn('beneficiario_id', $beneficiariosIds)
                ->whereMonth('fecha_hora', $mes->month)
                ->whereYear('fecha_hora', $mes->year)
                ->where('estado', 'completada')
                ->sum('duracion_minutos');
            
            $consumoMensual[] = [
                'mes' => $mes->format('M Y'),
                'minutos' => $minutos
            ];
        }
        
        // Compras recientes
        $comprasRecientes = Compra::where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('cliente.dashboard', compact(
            'cliente',
            'totalCompras',
            'totalGastado',
            'totalMinutosComprados',
            'totalMinutosDisponibles',
            'totalMinutosConsumidos',
            'beneficiariosActivos',
            'proximasClases',
            'progresoBeneficiarios',
            'consumoMensual',
            'comprasRecientes'
        ));
    }
}
