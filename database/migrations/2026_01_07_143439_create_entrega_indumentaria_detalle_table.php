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
        Schema::create('entrega_indumentaria_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrega_id')->constrained('entregas_indumentaria')->cascadeOnDelete();
            $table->foreignId('indumentaria_id')->constrained()->cascadeOnDelete();
            $table->integer('cantidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrega_indumentaria_detalle');
    }
};
