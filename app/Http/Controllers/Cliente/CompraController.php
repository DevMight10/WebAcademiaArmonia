<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Beneficiario;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\DistribucionCredito;
use App\Services\PrecioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    // Compra de Créditos

    public function create()
    {
        return view('cliente.compras.create');
    }

    // Almacena la compra completa con beneficiarios y distribución
    public function store(Request $request)
    {
        $request->validate([
            'minutos_totales' => 'required|integer|min:30',
            'beneficiarios' => 'required|array|min:1|max:4',
            'beneficiarios.*.user_id' => 'required|exists:users,id',
            'distribuciones' => 'required|array',
            'distribuciones.*' => 'required|integer|min:1',
        ]);

        // Validar que la suma de distribuciones = minutos totales
        $sumaDistribuciones = array_sum($request->distribuciones);
        if ($sumaDistribuciones != $request->minutos_totales) {
            return back()->withErrors([
                'distribuciones' => "La suma de distribuciones ($sumaDistribuciones) debe ser igual al total de minutos ({$request->minutos_totales})"
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            // Obtener el cliente actual (cuando esté el login, usar auth()->user()->cliente)
            // Por ahora usaremos el primer cliente
            $cliente = Cliente::first();

            // Crear la compra
            $subtotal = PrecioService::calcularSubtotal($request->minutos_totales);
            $porcentajeDescuento = PrecioService::calcularDescuento($request->minutos_totales);
            $montoDescuento = PrecioService::calcularMontoDescuento($request->minutos_totales);
            $total = PrecioService::calcularTotal($request->minutos_totales);

            $compra = Compra::create([
                'cliente_id' => $cliente->id,
                'minutos_totales' => $request->minutos_totales,
                'precio_por_minuto' => PrecioService::PRECIO_BASE_POR_MINUTO,
                'porcentaje_descuento' => $porcentajeDescuento,
                'subtotal' => $subtotal,
                'descuento' => $montoDescuento,
                'total' => $total,
                'estado' => 'Pendiente',
            ]);

            // Crear distribuciones de crédito para cada beneficiario
            foreach ($request->beneficiarios as $index => $beneficiarioData) {
                $beneficiario = Beneficiario::where('user_id', $beneficiarioData['user_id'])->first();

                if ($beneficiario) {
                    DistribucionCredito::create([
                        'compra_id' => $compra->id,
                        'beneficiario_id' => $beneficiario->id,
                        'minutos_asignados' => $request->distribuciones[$index],
                        'minutos_disponibles' => 0, // Se activarán cuando se confirme el pago
                        'estado' => 'pendiente',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('cliente.compras.confirmacion', $compra->id)
                ->with('success', '¡Compra registrada exitosamente! Espera la solicitud de pago.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar la compra: ' . $e->getMessage()])->withInput();
        }
    }

    // Muestra la confirmación de la compra

    public function confirmacion($id)
    {
        $compra = Compra::with(['distribuciones.beneficiario.user'])->findOrFail($id);

        return view('cliente.compras.confirmacion', compact('compra'));
    }

    // Muestra el historial de compras del cliente

    public function index()
    {
        // Cuando esté el login: $cliente = auth()->user()->cliente;
        $cliente = Cliente::first();

        $compras = Compra::where('cliente_id', $cliente->id)
            ->with(['distribuciones', 'pago'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.compras.index', compact('compras'));
    }
}
