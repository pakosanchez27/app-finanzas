<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'nombre',
    'tipo',
    'descripcion',
    'moneda',
    'user_id'
])]
class EspacioFinanciero extends Model
{
    use SoftDeletes;

    protected $table = 'espacios_financieros';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
