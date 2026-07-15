<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'espacio_financiero_id',
    'nombre',
    'tipo',
    'color',
    'icono',
])]
class CategoriaGasto extends Model
{
    protected $table = 'categorias_gasto';

    public function espacioFinanciero()
    {
        return $this->belongsTo(EspacioFinanciero::class);
    }

    public function partidas()
    {
        return $this->hasMany(PartidaPresupuesto::class, 'categorias_gasto_id');
    }
}
