<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamp('enrolled_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            /*
             * Porcentaje de avance.
             */
            $table->decimal(
                'progress',
                5,
                2
            )->default(0);

            $table->boolean('status')
                ->default(true);

            $table->timestamps();

            /*
             * Un usuario no puede estar
             * inscrito dos veces en el mismo curso.
             */
            $table->unique([
                'course_id',
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
        Schema::dropIfExists('course_enrollments');
    }
};