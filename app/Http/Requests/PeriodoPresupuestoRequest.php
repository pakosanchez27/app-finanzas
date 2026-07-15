<?php

namespace App\Http\Requests;

use App\Models\EspacioFinanciero;
use App\Models\PeriodoPresupuesto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PeriodoPresupuestoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $espacio = $this->route('espacio');
        $periodo = $this->route('periodo');

        return $espacio instanceof EspacioFinanciero
            && $periodo instanceof PeriodoPresupuesto
            && $espacio->user_id === $this->user()?->id
            && $periodo->espacio_financiero_id === $espacio->id;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:120'],
            'tipo' => ['required', Rule::in(['semanal', 'quincenal', 'mensual', 'anual'])],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'ingreso_estimado' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'estado' => ['required', Rule::in(['borrador', 'activo', 'cerrado'])],
        ];
    }
}
