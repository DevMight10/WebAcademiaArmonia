<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador para gestión de compras por el coordinador
 */
class CompraCoordinadorController extends Controller
{
    /**
     * Mostrar lista de compras pendientes
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener todas las compras pendientes
        $compras = Compra::where('estado', 'Pendiente')
            ->with(['cliente.user', 'distribuciones.beneficiario.user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('coordinador.compras.index', compact('compras'));
    }

    /**
     * Obtener detalles de una compra específica
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $compra = Compra::with(['cliente.user', 'distribuciones.beneficiario.user'])
            ->findOrFail($id);
        
        return response()->json([
            'compra' => [
                'id' => $compra->id,
                'fecha' => $compra->created_at->format('d/m/Y H:i'),
                'minutos_totales' => $compra->minutos_totales,
                'total' => $compra->total,
                'porcentaje_descuento' => $compra->porcentaje_descuento,
                'estado' => $compra->estado,
                'cliente' => [
                    'nombre' => $compra->cliente->user->name,
                    'email' => $compra->cliente->user->email,
                    'ci' => $compra->cliente->ci,
                    'telefono' => $compra->cliente->telefono,
                ],
                'distribuciones' => $compra->distribuciones->map(function($dist) {
                    return [
                        'beneficiario' => $dist->beneficiario->user->name,
                        'email' => $dist->beneficiario->user->email,
                        'minutos_asignados' => $dist->minutos_asignados,
                    ];
                }),
            ]
        ]);
    }

    /**
     * Aprobar una compra y activar los créditos
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function aprobar(Request $request, $id)
    {
        $compra = Compra::with('distribuciones')->findOrFail($id);
        
        // Validar que esté pendiente
        if ($compra->estado !== 'Pendiente') {
            return response()->json([
                'message' => 'Solo se pueden aprobar compras pendientes'
            ], 400);
        }
        
        DB::beginTransaction();
        try {
            // Cambiar estado a Completada
            $compra->estado = 'Completada';
            $compra->save();
            
            // ACTIVAR LOS CRÉDITOS: copiar minutos_asignados a minutos_disponibles
            foreach ($compra->distribuciones as $distribucion) {
                $distribucion->minutos_disponibles = $distribucion->minutos_asignados;
                $distribucion->estado = 'activo';
                $distribucion->save();
            }
            
            DB::commit();
            
            return response()->json([
                'message' => 'Compra aprobada exitosamente. Créditos activados.',
                'compra' => $compra
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al aprobar la compra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar una compra
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function rechazar(Request $request, $id)
    {
        $compra = Compra::findOrFail($id);
        
        // Validar que esté pendiente
        if ($compra->estado !== 'Pendiente') {
            return response()->json([
                'message' => 'Solo se pueden rechazar compras pendientes'
            ], 400);
        }
        
        $request->validate([
            'motivo' => 'required|string|min:10'
        ]);
        
        DB::beginTransaction();
        try {
            // Cambiar estado a Rechazada
            $compra->estado = 'Rechazada';
            $compra->observaciones = $request->motivo; // Guardar motivo
            $compra->save();
            
            DB::commit();
            
            return response()->json([
                'message' => 'Compra rechazada',
                'compra' => $compra
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al rechazar la compra: ' . $e->getMessage()
            ], 500);
        }
    }
}
