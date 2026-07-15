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
        Schema::create('periodos_presupuesto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('espacio_financiero_id')
                ->constrained('espacios_financieros')
                ->cascadeOnDelete();

            $table->string('nombre');
            $table->enum('tipo', [
                'semanal',
                'quincenal',
                'mensual',
                'anual',
            ]);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('ingreso_estimado', 12, 2);
            $table->enum('estado', [
                'borrador',
                'activo',
                'cerrado',
            ]);
            $table->timestamps();

            $table->index([
                'espacio_financiero_id',
                'estado',
                'fecha_inicio',
            ], 'periodos_espacio_estado_fecha_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos_presupuesto');
    }
};
