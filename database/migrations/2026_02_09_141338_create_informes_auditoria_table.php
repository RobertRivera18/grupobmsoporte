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
        Schema::create('informes_auditoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auditoria_proceso_id')
                ->constrained('auditoria_proceso')
                ->cascadeOnDelete();
            $table->text('descripcion');
            $table->text('evidencia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informes_auditoria');
    }
};
