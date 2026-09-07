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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('entorno_id')
                ->nullable()
                ->constrained('entornos')
                ->nullOnDelete();

            $table->foreignId('generador_id')
                ->nullable()
                ->constrained('generadores')
                ->nullOnDelete();
            $table->json('equipos_json')->nullable();
            $table->unsignedInteger('potencia_total_w')->nullable();
            $table->decimal('capacidad_recomendada_kva', 8, 2)->nullable();
            $table->decimal('capacidad_recomendada_kw', 8, 2)->nullable();
            $table->string('nombre_cliente')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('estado')->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
