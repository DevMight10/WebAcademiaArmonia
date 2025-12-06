<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstrumentoRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', Rule::unique('instrumentos')->ignore($this->route('instrumento'))],
            'categoria' => ['required', 'in:basico,intermedio,avanzado,especializado'],
            'factor_costo' => ['required', 'numeric', 'min:1'],
            'estado' => ['boolean'],
        ];
    }


    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del instrumento es obligatorio.',
            'nombre.unique' => 'Ya existe un instrumento con ese nombre.',
            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.in' => 'La categoría seleccionada no es válida.',
            'factor_costo.required' => 'El factor de costo es obligatorio.',
            'factor_costo.min' => 'El factor de costo debe ser al menos 1.',
        ];
    }
}
