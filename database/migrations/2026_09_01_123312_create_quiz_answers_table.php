<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attempt_id')
                ->constrained('quiz_attempts')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            /*
             * Ejemplos:
             *
             * Selección única:
             * "5"
             *
             * Selección múltiple:
             * "[2,4,5]"
             *
             * Verdadero/Falso:
             * "true"
             *
             * Respuesta corta:
             * "Mi respuesta"
             */
            $table->longText('answer')
                ->nullable();

            /*
             * NULL mientras no haya
             * sido calificada.
             */
            $table->boolean('is_correct')
                ->nullable();

            /*
             * Puntos obtenidos.
             */
            $table->decimal(
                'points',
                10,
                2
            )->default(0);

            $table->timestamps();

            /*
             * Una respuesta por pregunta
             * dentro de cada intento.
             */
            $table->unique([
                'attempt_id',
                'question_id'
            ]);

            $table->index('question_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};