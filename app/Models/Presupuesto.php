<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'espacio_financiero_id',
    'periodo_presupuesto_id',
    'nombre',
    'ingreso_total',
    'gasto_planeado_total',
    'gasto_real_total',
    'disponible_estimado',
    'disponible_real',
    'riesgo',
    'estado',
])]
class Presupuesto extends Model
{
    protected function casts(): array
    {
        return [
            'ingreso_total' => 'decimal:2',
            'gasto_planeado_total' => 'decimal:2',
            'gasto_real_total' => 'decimal:2',
            'disponible_estimado' => 'decimal:2',
            'disponible_real' => 'decimal:2',
        ];
    }

    public function espacioFinanciero()
    {
        return $this->belongsTo(EspacioFinanciero::class);
    }

    public function periodoPresupuesto()
    {
        return $this->belongsTo(PeriodoPresupuesto::class);
    }

    public function partidas()
    {
        return $this->hasMany(PartidaPresupuesto::class);
    }
}
