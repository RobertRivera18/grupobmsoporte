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
        Schema::create('indicadores_anio', function (Blueprint $table) {
            $table->id();

            // Relación con indicador principal
            $table->foreignId('indicador_id')->constrained('indicadores')->onDelete('cascade');

            // Año del histórico
            $table->integer('anio');

            // Campos que cambian cada año
            $table->decimal('meta', 10, 2)->nullable();
            $table->decimal('resultado_obtenido', 10, 2)->nullable();
            $table->date('ultima_revision')->nullable();

            // Observación general
            $table->text('observacion')->nullable();

            $table->timestamps();

            // NO duplicar datos del mismo año
            $table->unique(['indicador_id', 'anio']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicadores_anio');
    }
};
