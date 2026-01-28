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
        Schema::create('incidencia_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incidencia_id')->constrained()->cascadeOnDelete();
            $table->string('archivo'); // ruta del archivo
            $table->string('tipo', 10); // jpg, pdf, docx
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencia_archivos');
    }
};
