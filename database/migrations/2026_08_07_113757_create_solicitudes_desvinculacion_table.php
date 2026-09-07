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
        Schema::create('solicitudes_desvinculacion', function (Blueprint $table) {
            $table->id();
            // Colaborador que se desvincula
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Cuadrilla a la que pertenecía (opcional)
            $table->foreignId('cuadrilla_id')
                ->nullable()
                ->constrained('cuadrillas')
                ->onDelete('set null');

            // Verificaciones de entrega
            $table->boolean('devolver_credencial')->default(false);
            $table->boolean('devolver_uniforme')->default(false);

            // Observaciones generales
            $table->text('observaciones')->nullable();

            // Control de flujo (TTHH, Sistemas, etc.)
            $table->string('etapa')->default('tthh')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_desvinculacion');
    }
};
