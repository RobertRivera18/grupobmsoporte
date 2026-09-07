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
        Schema::create('solicitud_desvinculacion_equipo', function (Blueprint $table) {
            $table->id();

            // Usamos nombres cortos explícitos para evitar el error de longitud en MySQL
            $table->foreignId('solicitud_desvinculacion_id');
            $table->foreign('solicitud_desvinculacion_id', 'sde_solicitud_id_fk')
                ->references('id')
                ->on('solicitudes_desvinculacion')
                ->onDelete('cascade');

            $table->foreignId('equipo_id');
            $table->foreign('equipo_id', 'sde_equipo_id_fk')
                ->references('id')
                ->on('equipos')
                ->onDelete('cascade');

            // Destino o estado seleccionado por Sistemas
            $table->enum('destino', ['entregado', 'traspasado', 'faltante']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_desvinculacion_equipo');
    }
};
