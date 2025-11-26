<?php

namespace App\Http\Requests\Coordinador;

use Illuminate\Foundation\Http\FormRequest;

class VerificarPagoRequest extends FormRequest
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
            'comprobante' => ['required', 'string'],
            'monto_verificado' => ['required', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'comprobante.required' => 'Debe ingresar el número o referencia del comprobante.',
            'monto_verificado.required' => 'Debe verificar el monto recibido.',
            'monto_verificado.min' => 'El monto debe ser mayor a 0.',
        ];
    }
}
