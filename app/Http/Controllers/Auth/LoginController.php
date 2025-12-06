<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\Auth\LoginRequest; // [NEW] Importar Request personalizado

class LoginController extends Controller
{
    /**
     * Mostrar formulario de inicio de sesión.
     * Redirige al dashboard si ya está autenticado.
     */
    public function showLoginForm()
    {
        // Si ya está autenticado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesar intento de login con rate limiting.
     * La autenticación y límite de intentos se manejan en LoginRequest.
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate(); // Maneja Rate Limiting y autenticación

        $request->session()->regenerate(); // Prevenir session fixation

        // Redirigir según el rol del usuario
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Cerrar sesión del usuario actual.
     * Invalida la sesión y regenera el token CSRF.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    }
}
