<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Beneficiario;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\DistribucionCredito;
use App\Models\User;
use App\Services\PrecioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador para la gestión de compras de créditos musicales
 *
 * Maneja todo el proceso de compra: desde la selección de minutos,
 * registro de beneficiarios, distribución de créditos y confirmación.
 */
class CompraController extends Controller
{
    /**
     * RF-01.2: Mostrar formulario de nueva compra
     *
     * Permite al cliente crear una nueva compra de créditos musicales.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('cliente.compras.create');
    }

    /**
     * Buscar beneficiario por email
     * 
     * Busca un usuario con rol "beneficiario" por su email.
     * Solo retorna beneficiarios que existen en el sistema.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarBeneficiario(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Buscar usuario con rol beneficiario
        $user = User::whereHas('role', function ($query) {
            $query->where('slug', 'beneficiario');
        })
        ->where('email', $request->email)
        ->with('beneficiario')
        ->first();

        if ($user && $user->beneficiario) {
            return response()->json([
                'found' => true,
                'beneficiario' => [
                    'user_id' => $user->id,
                    'beneficiario_id' => $user->beneficiario->id,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'ci' => $user->beneficiario->ci,
                    'telefono' => $user->beneficiario->telefono,
                ]
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'Beneficiario con ese email no existe'
        ], 404);
    }

    /**
     * Almacenar la compra completa con beneficiarios y distribución
     *
     * Proceso:
     * 1. Validar datos de entrada
     * 2. Verificar que la distribución suma el total
     * 3. Crear registro de compra
     * 4. Crear distribuciones para cada beneficiario
     * 5. Redirigir a confirmación
     *
     * @param Request $request Datos de la compra
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validar datos de entrada con mensajes personalizados
        $request->validate([
            'minutos_totales' => 'required|integer|min:30',
            'beneficiarios' => 'required|array|min:1|max:4',
            'beneficiarios.*.user_id' => 'required|exists:users,id',
            'distribuciones' => 'required|array',
            'distribuciones.*' => 'required|integer|min:1',
        ], [
            'minutos_totales.required' => 'Debes especificar la cantidad de minutos.',
            'minutos_totales.min' => 'La cantidad mínima es de 30 minutos.',
            'beneficiarios.required' => 'Debes registrar al menos un beneficiario.',
            'beneficiarios.max' => 'Máximo 4 beneficiarios permitidos.',
            'beneficiarios.*.user_id.exists' => 'Uno de los beneficiarios no existe.',
            'distribuciones.*.min' => 'Cada beneficiario debe tener al menos 1 minuto.',
        ]);

        // Validar que la suma de distribuciones sea igual al total de minutos
        $sumaDistribuciones = array_sum($request->distribuciones);
        if ($sumaDistribuciones != $request->minutos_totales) {
            return back()->withErrors([
                'distribuciones' => "La suma de distribuciones ($sumaDistribuciones min) debe ser igual al total ({$request->minutos_totales} min)"
            ])->withInput();
        }

        // Usar transacción para asegurar integridad de datos
        DB::beginTransaction();
        try {
            // Obtener el cliente autenticado
            $cliente = auth()->user()->cliente;

            // Validar que existe un cliente
            if (!$cliente) {
                return back()->withErrors([
                    'error' => 'No se encontró un perfil de cliente. Contacta al administrador.'
                ])->withInput();
            }

            // Calcular todos los valores monetarios usando el servicio
            $subtotal = PrecioService::calcularSubtotal($request->minutos_totales);
            $porcentajeDescuento = PrecioService::calcularDescuento($request->minutos_totales);
            $montoDescuento = PrecioService::calcularMontoDescuento($request->minutos_totales);
            $total = PrecioService::calcularTotal($request->minutos_totales);

            // Crear el registro de compra
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
                // Buscar el beneficiario por user_id
                $beneficiario = Beneficiario::where('user_id', $beneficiarioData['user_id'])->first();

                if ($beneficiario) {
                    // Crear la distribución de créditos
                    DistribucionCredito::create([
                        'compra_id' => $compra->id,
                        'beneficiario_id' => $beneficiario->id,
                        'minutos_asignados' => $request->distribuciones[$index],
                        'minutos_disponibles' => 0, // Se activan cuando se confirme el pago
                        'estado' => 'pendiente',
                    ]);
                }
            }

            // Confirmar la transacción
            DB::commit();

            // Redirigir a la página de confirmación
            return redirect()->route('cliente.compras.confirmacion', $compra->id)
                ->with('success', '¡Compra registrada exitosamente! Espera la solicitud de pago del coordinador.');

        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Error al procesar la compra: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Mostrar la confirmación de una compra realizada
     *
     * Muestra los detalles completos de la compra incluyendo:
     * - Información general (fecha, estado, total)
     * - Distribución de créditos entre beneficiarios
     * - Próximos pasos del proceso
     *
     * @param int $id ID de la compra
     * @return \Illuminate\View\View
     */
    public function confirmacion($id)
    {
        // Obtener la compra con sus relaciones
        $compra = Compra::with(['distribuciones.beneficiario.user'])->findOrFail($id);

        return view('cliente.compras.confirmacion', compact('compra'));
    }

    /**
     * Mostrar historial de compras del cliente
     *
     * Lista todas las compras realizadas por el cliente ordenadas
     * por fecha de creación (más recientes primero).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener el cliente autenticado
        $cliente = auth()->user()->cliente;

        // Validar que existe un cliente
        if (!$cliente) {
            return redirect()->route('cliente.dashboard')
                ->with('error', 'No se encontró un perfil de cliente. Contacta al administrador.');
        }

        // Obtener todas las compras del cliente con sus relaciones
        $compras = Compra::where('cliente_id', $cliente->id)
            ->with(['distribuciones.beneficiario.user', 'pago'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.compras.index', compact('compras'));
    }
}
