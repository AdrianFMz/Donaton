<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'client_ref' => ['required', 'uuid'],
            'amount_mxn' => ['required', 'numeric', 'min:1', 'max:1000000'],
            'message' => ['nullable', 'string', 'max:300'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount_mxn.required' => 'Ingresa una cantidad.',
            'amount_mxn.numeric'  => 'La cantidad debe ser numérica.',
            'amount_mxn.min'      => 'La cantidad debe ser mayor a 0.',
            'amount_mxn.max'      => 'La cantidad excede el límite permitido.',
        ];
    }
}