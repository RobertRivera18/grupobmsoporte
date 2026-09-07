<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            /*
             * Texto de la opción.
             */
            $table->text('option');

            /*
             * Indica si la opción es correcta.
             */
            $table->boolean('is_correct')
                ->default(false);

            /*
             * Orden de aparición.
             */
            $table->unsignedInteger('order')
                ->default(0);

            $table->timestamps();

            $table->index([
                'question_id',
                'order'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};