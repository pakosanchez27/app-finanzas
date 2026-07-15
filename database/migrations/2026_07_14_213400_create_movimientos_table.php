<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('espacio_financiero_id')
                ->constrained('espacios_financieros')
                ->cascadeOnDelete();

            $table->foreignId('categoria_gasto_id')
                ->nullable()
                ->constrained('categorias_gasto')
                ->nullOnDelete();

            $table->foreignId('presupuesto_id')
                ->nullable()
                ->constrained('presupuestos')
                ->nullOnDelete();

            $table->foreignId('partida_presupuesto_id')
                ->nullable()
                ->constrained('partidas_presupuesto')
                ->nullOnDelete();

            $table->enum('tipo', ['ingreso', 'gasto']);
            $table->string('nombre');
            $table->decimal('monto', 12, 2);
            $table->date('fecha_movimiento');
            $table->string('metodo_pago')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(
                ['espacio_financiero_id', 'fecha_movimiento', 'tipo'],
                'movimientos_espacio_fecha_tipo_idx',
            );
            $table->index(
                ['presupuesto_id', 'partida_presupuesto_id'],
                'movimientos_presupuesto_partida_idx',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
