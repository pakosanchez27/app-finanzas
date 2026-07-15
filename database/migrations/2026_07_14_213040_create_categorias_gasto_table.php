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
        Schema::create('categorias_gasto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('espacio_financiero_id')
                ->constrained('espacios_financieros')
                ->cascadeOnDelete();

            $table->string('nombre');
            $table->enum('tipo', ['fijo', 'variable']);
            $table->string('color');
            $table->string('icono');
            $table->timestamps();

            $table->index([
                'espacio_financiero_id',
                'tipo',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias_gasto');
    }
};
