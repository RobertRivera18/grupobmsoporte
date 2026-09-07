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
        Schema::create('mantenimiento_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_mantenimiento_id')
                ->constrained('registro_mantenimientos')
                ->onDelete('cascade');
            $table->foreignId('tipo_servicio_id')
                ->constrained('tipo_servicios')
                ->onDelete('restrict');
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_mantenimiento_detalles');
    }
};
