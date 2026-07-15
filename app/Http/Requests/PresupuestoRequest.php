<?php

namespace App\Http\Requests;

use App\Models\EspacioFinanciero;
use App\Models\Presupuesto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PresupuestoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $espacio = $this->route('espacio');
        $presupuesto = $this->route('presupuesto');

        return $espacio instanceof EspacioFinanciero
            && $espacio->user_id === $this->user()?->id
            && (! $presupuesto instanceof Presupuesto
                || $presupuesto->espacio_financiero_id === $espacio->id);
    }

    public function rules(): array
    {
        /** @var EspacioFinanciero $espacio */
        $espacio = $this->route('espacio');
        /** @var Presupuesto|null $presupuesto */
        $presupuesto = $this->route('presupuesto');

        return [
            'periodo_modo' => ['required', Rule::in(['existente', 'nuevo'])],
            'periodo_presupuesto_id' => [
                'exclude_unless:periodo_modo,existente',
                'nullable',
                'required_if:periodo_modo,existente',
                'integer',
                Rule::exists('periodos_presupuesto', 'id')
                    ->where('espacio_financiero_id', $espacio->id),
                Rule::unique('presupuestos', 'periodo_presupuesto_id')
                    ->ignore($presupuesto?->id),
            ],
            'periodo_nombre' => ['exclude_unless:periodo_modo,nuevo', 'nullable', 'required_if:periodo_modo,nuevo', 'string', 'max:120'],
            'periodo_tipo' => [
                'exclude_unless:periodo_modo,nuevo',
                'nullable',
                'required_if:periodo_modo,nuevo',
                Rule::in(['semanal', 'quincenal', 'mensual', 'anual']),
            ],
            'fecha_inicio' => ['exclude_unless:periodo_modo,nuevo', 'nullable', 'required_if:periodo_modo,nuevo', 'date'],
            'fecha_fin' => [
                'exclude_unless:periodo_modo,nuevo',
                'nullable',
                'required_if:periodo_modo,nuevo',
                'date',
                'after_or_equal:fecha_inicio',
            ],
            'ingreso_estimado' => [
                'exclude_unless:periodo_modo,nuevo',
                'nullable',
                'required_if:periodo_modo,nuevo',
                'numeric',
                'gt:0',
                'decimal:0,2',
            ],
            'nombre' => ['required', 'string', 'max:120'],
            'partidas' => ['required', 'array', 'min:1', 'max:50'],
            'partidas.*.id' => [
                'nullable',
                'integer',
                Rule::exists('partidas_presupuesto', 'id')
                    ->where('presupuesto_id', $presupuesto?->id ?? 0),
            ],
            'partidas.*.nombre' => ['required', 'string', 'max:120'],
            'partidas.*.categorias_gasto_id' => [
                'required',
                'integer',
                Rule::exists('categorias_gasto', 'id')
                    ->where('espacio_financiero_id', $espacio->id),
            ],
            'partidas.*.monto_planeado' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'partidas.*.fecha_objetivo' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'periodo_modo.required' => 'Indica si usarás un periodo existente o uno nuevo.',
            'periodo_presupuesto_id.required_if' => 'Selecciona un periodo presupuestario.',
            'periodo_presupuesto_id.exists' => 'El periodo seleccionado no pertenece a este espacio.',
            'periodo_presupuesto_id.unique' => 'Este periodo ya tiene un presupuesto. Puedes editar el existente.',
            'periodo_nombre.required_if' => 'Escribe un nombre para el periodo.',
            'periodo_nombre.max' => 'El nombre del periodo no puede superar 120 caracteres.',
            'periodo_tipo.required_if' => 'Selecciona la frecuencia del periodo.',
            'periodo_tipo.in' => 'La frecuencia seleccionada no es válida.',
            'fecha_inicio.required_if' => 'Selecciona la fecha de inicio.',
            'fecha_inicio.date' => 'La fecha de inicio no es válida.',
            'fecha_fin.required_if' => 'Selecciona la fecha de finalización.',
            'fecha_fin.date' => 'La fecha de finalización no es válida.',
            'fecha_fin.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha inicial.',
            'ingreso_estimado.required_if' => 'Ingresa el ingreso estimado del periodo.',
            'ingreso_estimado.numeric' => 'El ingreso estimado debe ser un número válido.',
            'ingreso_estimado.gt' => 'El ingreso estimado debe ser mayor que cero.',
            'ingreso_estimado.decimal' => 'El ingreso estimado puede tener hasta dos decimales.',
            'nombre.required' => 'Escribe un nombre para el presupuesto.',
            'nombre.max' => 'El nombre del presupuesto no puede superar 120 caracteres.',
            'partidas.required' => 'Agrega al menos una partida de gasto.',
            'partidas.min' => 'Agrega al menos una partida de gasto.',
            'partidas.max' => 'No puedes agregar más de 50 partidas.',
            'partidas.*.nombre.required' => 'Escribe el nombre de la partida.',
            'partidas.*.id.exists' => 'Una de las partidas no pertenece a este presupuesto.',
            'partidas.*.nombre.max' => 'El nombre de la partida no puede superar 120 caracteres.',
            'partidas.*.categorias_gasto_id.required' => 'Selecciona una categoría para la partida.',
            'partidas.*.categorias_gasto_id.exists' => 'La categoría seleccionada no pertenece a este espacio.',
            'partidas.*.monto_planeado.required' => 'Ingresa el monto planeado de la partida.',
            'partidas.*.monto_planeado.numeric' => 'El monto planeado debe ser un número válido.',
            'partidas.*.monto_planeado.gt' => 'El monto planeado debe ser mayor que cero.',
            'partidas.*.monto_planeado.decimal' => 'El monto planeado puede tener hasta dos decimales.',
            'partidas.*.fecha_objetivo.date' => 'La fecha objetivo no es válida.',
        ];
    }
}
