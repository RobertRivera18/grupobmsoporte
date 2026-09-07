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
        Schema::create('entorno_equipo', function (Blueprint $table) {
        $table->id();

        $table->foreignId('entorno_id')
            ->constrained('entornos')
            ->cascadeOnDelete();

        $table->foreignId('equipo_id')
            ->constrained('equiposelectricos')
            ->cascadeOnDelete();

        $table->unsignedInteger('cantidad_defecto')->default(0);
        $table->boolean('seleccionado_defecto')->default(false);
        $table->unsignedInteger('orden')->default(0);

        $table->timestamps();
        
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entorno_equipo');
    }
};
