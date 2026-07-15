<?php

namespace Database\Seeders;

use App\Models\EspacioFinanciero;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaGastoSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Vivienda', 'tipo' => 'fijo', 'color' => '#3B82F6', 'icono' => 'house'],
            ['nombre' => 'Servicios', 'tipo' => 'fijo', 'color' => '#8B5CF6', 'icono' => 'plug'],
            ['nombre' => 'Seguros', 'tipo' => 'fijo', 'color' => '#64748B', 'icono' => 'shield-check'],
            ['nombre' => 'Educación', 'tipo' => 'fijo', 'color' => '#06B6D4', 'icono' => 'graduation-cap'],
            ['nombre' => 'Alimentación', 'tipo' => 'variable', 'color' => '#22C55E', 'icono' => 'shopping-cart'],
            ['nombre' => 'Transporte', 'tipo' => 'variable', 'color' => '#F59E0B', 'icono' => 'car'],
            ['nombre' => 'Salud', 'tipo' => 'variable', 'color' => '#EF4444', 'icono' => 'heart-pulse'],
            ['nombre' => 'Entretenimiento', 'tipo' => 'variable', 'color' => '#EC4899', 'icono' => 'gamepad-2'],
        ];

        EspacioFinanciero::query()
            ->select('id')
            ->chunkById(100, function ($espacios) use ($categorias): void {
                foreach ($espacios as $espacio) {
                    foreach ($categorias as $categoria) {
                        DB::table('categorias_gasto')->updateOrInsert(
                            [
                                'espacio_financiero_id' => $espacio->id,
                                'nombre' => $categoria['nombre'],
                            ],
                            fn (bool $existe): array => [
                                ...$categoria,
                                ...($existe ? [] : ['created_at' => now()]),
                                'updated_at' => now(),
                            ],
                        );
                    }
                }
            });
    }
}
