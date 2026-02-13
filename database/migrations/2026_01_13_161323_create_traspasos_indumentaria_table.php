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
        Schema::create('traspasos_indumentaria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ubicacion_origen_id')
                ->constrained('ubicaciones')
                ->cascadeOnDelete();

            $table->foreignId('ubicacion_destino_id')
                ->constrained('ubicaciones')
                ->cascadeOnDelete();

            $table->date('fecha');

            $table->text('observacion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traspasos_indumentaria');
    }
};
