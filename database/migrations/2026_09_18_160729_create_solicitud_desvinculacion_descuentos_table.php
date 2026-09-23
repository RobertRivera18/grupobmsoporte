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
        Schema::create('solicitud_desvinculacion_descuentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')
                  ->constrained('solicitudes_desvinculacion')
                  ->cascadeOnDelete();
            $table->string('concepto');
            $table->decimal('valor', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_desvinculacion_descuentos');
    }
};
