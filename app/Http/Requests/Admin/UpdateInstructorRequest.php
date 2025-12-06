<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstructorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // TODO: Implement authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $instructor = $this->route('instructore'); // Laravel pluraliza 'instructor' a 'instructore' en rutas resource
        $userId = $instructor ? $instructor->user_id : null;

        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'ci' => ['required', 'string', 'max:10', 'regex:/^\d{1,8}(SC|LP|CB|OR|PT|TJ|BE|PD|CH)$/', Rule::unique('instructores')->ignore($instructor)],
            'telefono' => ['required', 'numeric', 'digits:8'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'categoria' => ['required', 'in:regular,premium,invitado'],
            'especialidades' => ['nullable', 'array'],
            'especialidades.*' => ['exists:instrumentos,id'],
            'estado' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'ci.required' => 'El CI es obligatorio.',
            'ci.unique' => 'Ya existe un instructor con ese CI.',
            'ci.regex' => 'El formato del CI no es válido. Debe tener hasta 8 dígitos y una extensión departamental (ej: 12345678SC).',
            'ci.max' => 'El CI no debe exceder los 10 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.numeric' => 'El teléfono solo debe contener números.',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
            'email.required' => 'El email es obligatorio.',
            'email.unique' => 'Ya existe un instructor con ese email.',
            'categoria.required' => 'La categoría es obligatoria.',
        ];
    }
}
