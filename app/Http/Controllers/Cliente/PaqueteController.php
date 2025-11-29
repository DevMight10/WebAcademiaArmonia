<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Services\PrecioService;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de paquetes de créditos musicales
 *
 * Este controlador maneja la visualización de paquetes predefinidos
 * y el cálculo de precios personalizados para compra de créditos.
 */
class PaqueteController extends Controller
{
    /**
     * RF-01.1: Visualizar Paquetes de Créditos
     *
     * Muestra todos los paquetes predefinidos disponibles para compra
     * con sus respectivos descuentos escalonados (5% cada 300 minutos).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener todos los paquetes predefinidos desde el servicio
        $paquetes = PrecioService::obtenerPaquetes();

        return view('cliente.paquetes.index', compact('paquetes'));
    }

    /**
     * Calcular precio para cantidad personalizada (AJAX)
     *
     * Este método se usa para calcular en tiempo real el precio
     * de una cantidad personalizada de minutos ingresada por el cliente.
     *
     * @param Request $request Debe contener 'minutos' (integer >= 30)
     * @return \Illuminate\Http\JsonResponse
     */
    public function calcularPrecio(Request $request)
    {
        // Validar que se envíen al menos 30 minutos
        $request->validate([
            'minutos' => 'required|integer|min:30'
        ], [
            'minutos.required' => 'Debes ingresar una cantidad de minutos.',
            'minutos.integer' => 'La cantidad debe ser un número entero.',
            'minutos.min' => 'La cantidad mínima es de 30 minutos.',
        ]);

        $minutos = $request->minutos;

        // Retornar todos los cálculos de precio
        return response()->json([
            'minutos' => $minutos,
            'subtotal' => PrecioService::calcularSubtotal($minutos),
            'porcentaje_descuento' => PrecioService::calcularDescuento($minutos),
            'monto_descuento' => PrecioService::calcularMontoDescuento($minutos),
            'total' => PrecioService::calcularTotal($minutos),
            'nombre_paquete' => PrecioService::obtenerNombrePaquete($minutos),
        ]);
    }
}
