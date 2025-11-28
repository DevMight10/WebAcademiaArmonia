<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Display pending payment orders.
     */
    public function index()
    {
        //
    }

    /**
     * Show payment request form.
     */
    public function solicitarPago(string $compraId)
    {
        //
    }

    /**
     * Process payment request.
     */
    public function enviarSolicitudPago(Request $request, string $compraId)
    {
        //
    }

    /**
     * Show payment verification form.
     */
    public function verificarPago(string $compraId)
    {
        //
    }

    /**
     * Confirm payment and activate credits.
     */
    public function confirmarPago(Request $request, string $compraId)
    {
        //
    }
}
