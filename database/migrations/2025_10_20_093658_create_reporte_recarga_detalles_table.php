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
          Schema::create('reporte_recarga_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('reports')->onDelete('cascade');
            $table->foreignId('cuadrilla_id')->constrained('cuadrillas')->onDelete('cascade');
            $table->decimal('valor_recarga', 8, 2)->default(10.50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte_recarga_detalles');
    }
};
