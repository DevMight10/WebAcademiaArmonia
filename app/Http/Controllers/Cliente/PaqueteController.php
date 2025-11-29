<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Services\PrecioService;
use Illuminate\Http\Request;

class PaqueteController extends Controller
{
    // RF-01.1: Visualizar Paquetes de Créditos Muestra todos los paquetes disponibles con descuentos
    public function index()
    {
        $paquetes = PrecioService::obtenerPaquetes();

        return view('cliente.paquetes.index', compact('paquetes'));
    }

    // Calcula el precio para una cantidad personalizada (AJAX)

    public function calcularPrecio(Request $request)
    {
        $request->validate([
            'minutos' => 'required|integer|min:30'
        ]);

        $minutos = $request->minutos;

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
