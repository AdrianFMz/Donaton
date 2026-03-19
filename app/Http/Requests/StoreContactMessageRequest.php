<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // contacto público
    }

    public function rules(): array
    {
        return [
            'nombre'  => ['required', 'string', 'min:2', 'max:80'],
            'email'   => ['required', 'email', 'max:120'],
            'asunto'  => ['nullable', 'string', 'max:120'],
            'mensaje' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }
}