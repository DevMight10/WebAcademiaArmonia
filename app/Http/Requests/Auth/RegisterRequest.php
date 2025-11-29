<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'ci' => ['required', 'string', 'max:10', 'unique:users,ci', 'regex:/^\d{1,8}(SC|LP|CB|OR|PT|TJ|BE|PD|CH)$/'],
            'telefono' => ['required', 'numeric', 'digits:8', 'unique:users,telefono'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'tipo_usuario' => ['required', 'array', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.regex' => 'El nombre solo debe contener letras y espacios.',
            'email.required' => 'El email es obligatorio.',
            'email.unique' => 'Este email ya está registrado.',
            'ci.required' => 'El CI es obligatorio.',
            'ci.unique' => 'Este CI ya está registrado.',
            'ci.regex' => 'El formato del CI no es válido. Debe tener hasta 8 dígitos y una extensión departamental (ej: 12345678SC).',
            'ci.max' => 'El CI no debe exceder los 10 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.numeric' => 'El teléfono solo debe contener números.',
            'telefono.digits' => 'El teléfono debe tener 8 dígitos.',
            'telefono.unique' => 'Este teléfono ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'tipo_usuario.required' => 'Debes seleccionar al menos un tipo de usuario.',
        ];
    }
}
