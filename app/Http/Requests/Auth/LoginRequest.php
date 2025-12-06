<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para esta petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para el formulario de login.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Mensajes personalizados para errores de validación.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Debe ingresar un email válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }

    /**
     * Intentar autenticar las credenciales del usuario.
     * Incluye protección contra ataques de fuerza bruta.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // Verificar límite de intentos antes de procesar
        $this->ensureIsNotRateLimited();

        if (! \Illuminate\Support\Facades\Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Incrementar contador de intentos fallidos
            \Illuminate\Support\Facades\RateLimiter::hit($this->throttleKey());

            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Limpiar contador si el login fue exitoso
        \Illuminate\Support\Facades\RateLimiter::clear($this->throttleKey());
    }

    /**
     * Verificar que no se haya excedido el límite de intentos.
     * Máximo 5 intentos por minuto por email+IP.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! \Illuminate\Support\Facades\RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($this->throttleKey());

        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Generar clave única para rate limiting.
     * Combina email + IP para prevenir ataques distribuidos.
     */
    public function throttleKey(): string
    {
        return \Illuminate\Support\Str::transliterate(\Illuminate\Support\Str::lower($this->input('email')).'|'.$this->ip());
    }
}
