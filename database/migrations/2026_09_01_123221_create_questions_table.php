<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            /*
             * Tipos:
             *
             * single_choice
             * multiple_choice
             * true_false
             * short_answer
             * long_answer
             * numeric
             */
            $table->string('question_type');

            /*
             * Pregunta.
             *
             * Puede contener HTML.
             */
            $table->longText('question');

            /*
             * Explicación que se puede mostrar
             * después de responder.
             */
            $table->longText('explanation')->nullable();

            /*
             * Valor de la pregunta.
             */
            $table->decimal(
                'points',
                8,
                2
            )->default(1);

            /*
             * Dificultad.
             */
            $table->enum('difficulty', [
                'easy',
                'medium',
                'hard'
            ])->default('medium');

            $table->boolean('status')
                ->default(true);

            /*
             * Usuario que creó la pregunta.
             */
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index([
                'question_type',
                'status'
            ]);

            $table->index('difficulty');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};