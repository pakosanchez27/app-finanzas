<?php

namespace App\Http\Requests;

use App\Models\EspacioFinanciero;
use App\Models\PartidaPresupuesto;
use App\Models\Presupuesto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PartidaPresupuestoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $espacio = $this->route('espacio');
        $presupuesto = $this->route('presupuesto');
        $partida = $this->route('partida');

        return $espacio instanceof EspacioFinanciero
            && $presupuesto instanceof Presupuesto
            && $espacio->user_id === $this->user()?->id
            && $presupuesto->espacio_financiero_id === $espacio->id
            && (! $partida instanceof PartidaPresupuesto
                || $partida->presupuesto_id === $presupuesto->id);
    }

    public function rules(): array
    {
        /** @var EspacioFinanciero $espacio */
        $espacio = $this->route('espacio');

        return [
            'nombre' => ['required', 'string', 'max:120'],
            'categorias_gasto_id' => [
                'required',
                'integer',
                Rule::exists('categorias_gasto', 'id')
                    ->where('espacio_financiero_id', $espacio->id),
            ],
            'monto_planeado' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'fecha_objetivo' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Escribe el nombre de la partida.',
            'categorias_gasto_id.required' => 'Selecciona una categoría.',
            'categorias_gasto_id.exists' => 'La categoría no pertenece a este espacio.',
            'monto_planeado.required' => 'Ingresa el monto planeado.',
            'monto_planeado.numeric' => 'El monto planeado debe ser un número válido.',
            'monto_planeado.gt' => 'El monto planeado debe ser mayor que cero.',
            'monto_planeado.decimal' => 'El monto planeado puede tener hasta dos decimales.',
            'fecha_objetivo.date' => 'La fecha objetivo no es válida.',
        ];
    }
}
