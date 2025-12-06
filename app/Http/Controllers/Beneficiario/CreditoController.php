<?php

namespace App\Http\Controllers\Beneficiario;

use App\Http\Controllers\Controller;
use App\Models\DistribucionCredito;
use Illuminate\Http\Request;

/**
 * Controlador para gestión de créditos del beneficiario
 */
class CreditoController extends Controller
{
    /**
     * Mostrar lista de créditos del beneficiario
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener el beneficiario autenticado
        $user = auth()->user();
        
        // Buscar el beneficiario asociado al usuario
        $beneficiario = $user->beneficiario;
        
        if (!$beneficiario) {
            return redirect()->route('beneficiario.dashboard')
                ->with('error', 'No se encontró un perfil de beneficiario asociado.');
        }
        
        // Obtener todas las distribuciones de crédito del beneficiario
        $distribuciones = DistribucionCredito::where('beneficiario_id', $beneficiario->id)
            ->with(['compra.cliente.user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calcular totales
        $totalAsignado = $distribuciones->sum('minutos_asignados');
        $totalDisponible = $distribuciones->sum('minutos_disponibles');
        $totalConsumido = $distribuciones->sum('minutos_consumidos');
        
        return view('beneficiario.creditos.index', compact(
            'distribuciones',
            'totalAsignado',
            'totalDisponible',
            'totalConsumido'
        ));
    }
}
