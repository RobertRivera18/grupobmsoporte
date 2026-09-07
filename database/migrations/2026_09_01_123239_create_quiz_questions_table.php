<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_id')
                ->constrained('quizzes')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            /*
             * Orden dentro del cuestionario.
             */
            $table->unsignedInteger('order')
                ->default(0);

            /*
             * Permite modificar el valor
             * de una pregunta únicamente
             * para este cuestionario.
             */
            $table->decimal(
                'points',
                8,
                2
            )->nullable();

            $table->timestamps();

            /*
             * Una pregunta no puede repetirse
             * dos veces en el mismo cuestionario.
             */
            $table->unique([
                'quiz_id',
                'question_id'
            ]);

            $table->index([
                'quiz_id',
                'order'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};