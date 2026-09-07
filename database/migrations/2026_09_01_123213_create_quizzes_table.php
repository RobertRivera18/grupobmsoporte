<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->foreignId('module_id')
                ->nullable()
                ->constrained('course_modules')
                ->nullOnDelete();

            $table->string('title');

            $table->text('description')->nullable();

            /*
             * Tiempo máximo en minutos.
             * NULL = sin límite.
             */
            $table->unsignedInteger('time_limit')->nullable();

            /*
             * Porcentaje mínimo para aprobar.
             */
            $table->decimal(
                'passing_score',
                5,
                2
            )->default(70);

            /*
             * NULL = intentos ilimitados.
             */
            $table->unsignedInteger('max_attempts')->nullable();

            /*
             * Aleatorización
             */
            $table->boolean('random_questions')
                ->default(false);

            $table->boolean('random_options')
                ->default(false);

            /*
             * Mostrar resultado al finalizar.
             */
            $table->boolean('show_results')
                ->default(true);

            $table->boolean('status')
                ->default(true);

            $table->timestamps();

            $table->index([
                'course_id',
                'status'
            ]);

            $table->index('module_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};