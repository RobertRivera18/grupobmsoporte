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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')
                ->constrained('course_modules')
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('slug');

            /*
             * Contenido HTML del editor
             * Quill / CKEditor
             */
            $table->longText('content')->nullable();

            /*
             * Video externo
             * YouTube, Vimeo, etc.
             */
            $table->string('video_url')->nullable();

            /*
             * Documento PDF
             */
            $table->string('pdf_path')->nullable();

            $table->unsignedInteger('order')->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->unique([
                'module_id',
                'slug'
            ]);

            $table->index([
                'module_id',
                'status'
            ]);
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
