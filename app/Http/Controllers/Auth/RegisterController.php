<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Cliente;
use App\Models\Beneficiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        // Si ya está autenticado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        // Validar datos del formulario
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'ci' => ['required', 'string', 'max:20', 'unique:clientes,ci', 'unique:beneficiarios,ci'],
            'telefono' => ['required', 'string', 'max:20'],
            'tipo_usuario' => ['required', 'array', 'min:1'],
            'tipo_usuario.*' => ['in:cliente,beneficiario'],
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'ci.required' => 'El CI es obligatorio.',
            'ci.unique' => 'Este CI ya está registrado.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'tipo_usuario.required' => 'Debes seleccionar al menos un tipo de usuario.',
            'tipo_usuario.min' => 'Debes seleccionar al menos un tipo de usuario.',
        ]);

        DB::beginTransaction();

        try {
            // Determinar el rol principal
            $esCliente = in_array('cliente', $validated['tipo_usuario']);
            $esBeneficiario = in_array('beneficiario', $validated['tipo_usuario']);

            // Por defecto, si es solo beneficiario, ese es su rol
            // Si es cliente (con o sin beneficiario), el rol es cliente
            $rolSlug = $esCliente ? 'cliente' : 'beneficiario';
            $role = Role::where('slug', $rolSlug)->first();

            // Crear usuario
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $role->id,
                'email_verified_at' => now(),
            ]);

            // Crear perfil de cliente si corresponde
            if ($esCliente) {
                Cliente::create([
                    'user_id' => $user->id,
                    'ci' => $validated['ci'],
                    'telefono' => $validated['telefono'],
                ]);
            }

            // Crear perfil de beneficiario si corresponde
            if ($esBeneficiario) {
                Beneficiario::create([
                    'user_id' => $user->id,
                    'ci' => $validated['ci'],
                    'telefono' => $validated['telefono'],
                ]);
            }

            DB::commit();

            // Autenticar automáticamente al usuario
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', '¡Registro exitoso! Bienvenido a Academia Armonía.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Ocurrió un error al registrar tu cuenta. Por favor, intenta nuevamente.'
            ])->withInput();
        }
    }
}
