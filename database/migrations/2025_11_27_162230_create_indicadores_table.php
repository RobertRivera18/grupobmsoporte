<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicadores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('area_id')
                  ->constrained('areas')
                  ->cascadeOnDelete();

            $table->string('nombre');
            $table->text('forma_calculo')->nullable();
            $table->string('meta')->nullable();

            $table->foreignId('responsable_id')
                  ->constrained('users')
                ;

            $table->date('ultima_fecha_revision')->nullable();
            $table->string('resultado_obtenido')->nullable();
            $table->text('accion_tomar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicadores');
    }
};
