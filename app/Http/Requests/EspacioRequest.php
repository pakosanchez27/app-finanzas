<?php

namespace App\Http\Requests;

use App\TipoEspacios;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use Override;

class EspacioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del espacio es obligatorio',
            'tipo.required' => 'Debes seleccionar el tipo de espacio',
            'descripcion.required' => 'Debes escribir una descripcion corta de tu espacio',
            'moneda.required' => 'Debes seleccionar la moneda que usaras para el espacio'
        ];
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required'],
            'tipo' => ['required', new Enum(TipoEspacios::class)],
            'descripcion' => ['required'],
            'moneda' => ['required', Rule::in(['mxn', 'usd', 'euro'])]
        ];
    }
}
