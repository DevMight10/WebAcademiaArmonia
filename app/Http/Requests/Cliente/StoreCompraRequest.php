<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompraRequest extends FormRequest
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
            'minutos_totales' => ['required', 'integer', 'min:30'],
            'beneficiarios' => ['required', 'array', 'min:1', 'max:4'],
            'beneficiarios.*.nombre' => ['required', 'string', 'max:255'],
            'beneficiarios.*.apellido' => ['required', 'string', 'max:255'],
            'beneficiarios.*.ci' => ['required', 'string', 'max:20'],
            'beneficiarios.*.telefono' => ['required', 'string', 'max:20'],
            'beneficiarios.*.email' => ['required', 'email', 'max:255'],
            'beneficiarios.*.minutos' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $minutosDistribuidos = collect($this->beneficiarios)->sum('minutos');

            if ($minutosDistribuidos != $this->minutos_totales) {
                $validator->errors()->add(
                    'beneficiarios',
                    'La suma de minutos distribuidos debe ser igual al total de minutos comprados.'
                );
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'minutos_totales.required' => 'Debe especificar la cantidad de minutos.',
            'minutos_totales.min' => 'La compra mínima es de 30 minutos.',
            'beneficiarios.required' => 'Debe registrar al menos un beneficiario.',
            'beneficiarios.max' => 'Máximo 4 beneficiarios por compra.',
            'beneficiarios.*.nombre.required' => 'El nombre del beneficiario es obligatorio.',
            'beneficiarios.*.email.required' => 'El email del beneficiario es obligatorio.',
        ];
    }
}
