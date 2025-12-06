<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstrumentoRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para esta petición.
     */
    public function authorize(): bool
    {
        return true; // Autorización manejada por middleware
    }

    /**
     * Reglas de validación para crear un instrumento.
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'unique:instrumentos,nombre'],
            'categoria' => ['required', 'in:basico,intermedio,avanzado,especializado'],
            'factor_costo' => ['required', 'numeric', 'min:1'],
            'estado' => ['boolean'],
        ];
    }

    /**
     * Mensajes personalizados para errores de validación.
     */
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
