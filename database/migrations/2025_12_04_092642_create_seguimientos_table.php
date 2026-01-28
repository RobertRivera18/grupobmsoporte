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
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();

            // Relación con IndicadorAnio (cada seguimiento pertenece a una versión anual del indicador)
            $table->foreignId('indicador_anio_id')
                ->constrained('indicadores_anio')
                ->onDelete('cascade');

            // Mes de seguimiento (1-12)
            $table->unsignedTinyInteger('mes');

            // Valor del mes
            $table->string('valor')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};
