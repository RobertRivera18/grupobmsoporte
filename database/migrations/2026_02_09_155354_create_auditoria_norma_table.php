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
        Schema::create('auditoria_norma', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auditoria_proceso_id')
                ->constrained('auditoria_proceso')
                ->cascadeOnDelete();

            $table->foreignId('norma_iso_id')
                ->constrained('normas_iso')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria_norma');
    }
};
