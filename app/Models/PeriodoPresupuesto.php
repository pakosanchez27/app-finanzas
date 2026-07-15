<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'espacio_financiero_id',
    'nombre',
    'tipo',
    'fecha_inicio',
    'fecha_fin',
    'ingreso_estimado',
    'estado',
])]
class PeriodoPresupuesto extends Model
{
    protected $table = 'periodos_presupuesto';

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'ingreso_estimado' => 'decimal:2',
        ];
    }

    public function espacioFinanciero()
    {
        return $this->belongsTo(EspacioFinanciero::class);
    }

    public function presupuesto()
    {
        return $this->hasOne(Presupuesto::class);
    }
}
