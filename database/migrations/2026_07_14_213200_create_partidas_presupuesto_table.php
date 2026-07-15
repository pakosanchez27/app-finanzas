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
        Schema::create('partidas_presupuesto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('presupuesto_id')
                ->constrained('presupuestos')
                ->cascadeOnDelete();

            $table->foreignId('categorias_gasto_id')
                ->nullable()
                ->constrained('categorias_gasto')
                ->nullOnDelete();

            $table->string('nombre');
            $table->enum('tipo', ['ingreso', 'gasto']);
            $table->decimal('monto_planeado', 12, 2);
            $table->decimal('monto_real', 12, 2);
            $table->date('fecha_objetivo')->nullable();
            $table->enum('estado', [
                'pendiente',
                'parcial',
                'completado',
                'cancelado',
            ]);
            $table->string('referencia_tipo')->nullable();
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->timestamps();

            $table->index([
                'presupuesto_id',
                'tipo',
                'estado',
            ]);
            $table->index([
                'referencia_tipo',
                'referencia_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidas_presupuesto');
    }
};
