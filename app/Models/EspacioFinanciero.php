<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable([
    'nombre',
    'tipo',
    'descripcion',
    'moneda',
    'user_id',
])]
class EspacioFinanciero extends Model
{
    use SoftDeletes;

    protected $table = 'espacios_financieros';

    protected static function booted(): void
    {
        static::saving(function (EspacioFinanciero $espacio): void {
            if ($espacio->isDirty('nombre') || blank($espacio->slug)) {
                $espacio->slug = static::generateUniqueSlug(
                    $espacio->nombre,
                    $espacio->getKey(),
                );
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    private static function generateUniqueSlug(string $nombre, int|string|null $ignoreId = null): string
    {
        $baseSlug = Str::slug($nombre) ?: 'espacio';
        $slug = $baseSlug;
        $suffix = 2;

        while (static::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $baseSlug.'-'.$suffix++;
        }

        return $slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
