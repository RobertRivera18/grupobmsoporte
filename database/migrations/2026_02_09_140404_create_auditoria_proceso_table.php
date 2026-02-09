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
        Schema::create('auditoria_proceso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auditoria_id')
                ->constrained('auditorias')
                ->cascadeOnDelete();

            $table->foreignId('area_id')
                ->constrained('areas')
                ->cascadeOnDelete();

            $table->foreignId('auditor_id')
                ->constrained('users')
                ->cascadeOnUpdate();
                
            $table->foreignId('responsable_id')
                ->constrained('users')
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria_proceso');
    }
};
