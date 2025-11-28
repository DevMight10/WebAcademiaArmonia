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
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'ci' => ['required', 'string', 'max:20', Rule::unique('instructores')->ignore($this->route('instructor'))],
            'telefono' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('instructores')->ignore($this->route('instructor'))],
            'categoria' => ['required', 'in:regular,premium,invitado'],
            'factor_costo' => ['required', 'numeric', 'min:1'],
            'especialidades' => ['array'],
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
            'email.required' => 'El email es obligatorio.',
            'email.unique' => 'Ya existe un instructor con ese email.',
            'categoria.required' => 'La categoría es obligatoria.',
            'factor_costo.required' => 'El factor de costo es obligatorio.',
        ];
    }
}
