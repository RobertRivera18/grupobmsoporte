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
        Schema::create('informe_no_conformidads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('informe_auditoria_id')
                ->constrained('informes_auditoria')
                ->cascadeOnDelete();
            $table->text('descripcion');
            $table->text('evidencia')->nullable();

            $table->foreignId('norma_iso_id')
                ->constrained('normas_iso')
                ->restrictOnDelete();
            $table->enum('tipo', ['NC', 'O', 'OM']);
            // NC = No conformidad
            // O  = Observación
            // OM = Oportunidad de mejora

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informe_no_conformidads');
    }
};
