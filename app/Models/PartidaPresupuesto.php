<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'presupuesto_id',
    'categorias_gasto_id',
    'nombre',
    'tipo',
    'monto_planeado',
    'monto_real',
    'fecha_objetivo',
    'estado',
    'referencia_tipo',
    'referencia_id',
])]
class PartidaPresupuesto extends Model
{
    protected $table = 'partidas_presupuesto';

    protected function casts(): array
    {
        return [
            'monto_planeado' => 'decimal:2',
            'monto_real' => 'decimal:2',
            'fecha_objetivo' => 'date',
        ];
    }

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function categoriaGasto()
    {
        return $this->belongsTo(CategoriaGasto::class, 'categorias_gasto_id');
    }
}
