<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lesson_id')
                ->constrained('lessons')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamp('completed_at')
                ->nullable();

            $table->boolean('completed')
                ->default(false);

            $table->timestamps();

            /*
             * Un registro de progreso por
             * usuario y lección.
             */
            $table->unique([
                'lesson_id',
                'user_id'
            ]);

            $table->index([
                'user_id',
                'completed'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
    }
};