<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_id')
                ->constrained('quizzes')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
             * Número de intento:
             *
             * 1
             * 2
             * 3
             */
            $table->unsignedInteger('attempt_number')
                ->default(1);

            $table->timestamp('started_at');

            $table->timestamp('finished_at')
                ->nullable();

            /*
             * Puntos obtenidos.
             */
            $table->decimal(
                'score',
                10,
                2
            )->default(0);

            /*
             * Porcentaje obtenido.
             */
            $table->decimal(
                'percentage',
                5,
                2
            )->default(0);

            $table->boolean('passed')
                ->default(false);

            /*
             * in_progress
             * completed
             * abandoned
             */
            $table->string('status')
                ->default('in_progress');

            $table->timestamps();

            $table->index([
                'quiz_id',
                'user_id'
            ]);

            $table->index([
                'user_id',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};