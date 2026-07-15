<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('espacio_financiero_id')
                ->constrained('espacios_financieros')
                ->cascadeOnDelete();

            $table->foreignId('periodo_presupuesto_id')
                ->constrained('periodos_presupuesto')
                ->cascadeOnDelete();
            $table->string('nombre');
            $table->decimal('ingreso_total', 12, 2);
            $table->decimal('gasto_planeado_total', 12, 2);
            $table->decimal('gasto_real_total', 12, 2);
            $table->decimal('disponible_estimado', 12, 2);
            $table->decimal('disponible_real', 12, 2);
            $table->enum('riesgo', ['bajo', 'medio', 'alto']);

            $table->enum('estado', [
                'borrador',
                'activo',
                'cerrado',
            ]);

            $table->timestamps();

            $table->index([
                'espacio_financiero_id',
                'estado',
            ]);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
