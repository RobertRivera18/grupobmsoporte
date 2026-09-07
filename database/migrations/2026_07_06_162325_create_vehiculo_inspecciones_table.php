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
        Schema::create('vehiculo_inspecciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('cascade');
            $table->date('fecha');
            $table->string('tecnico_encargado');
            $table->integer('kilometraje');
            $table->enum('tipo_equipo', ['propio', 'alquilado'])->default('propio');
            $table->json('checklist');
            $table->text('observaciones')->nullable();
            $table->text('choques_golpes')->nullable();
            $table->string('revisado_por_nombre')->nullable();
            $table->string('tecnico_responsable_nombre')->nullable();
            $table->text('recomendaciones_mantenimiento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculo_inspecciones');
    }
};
