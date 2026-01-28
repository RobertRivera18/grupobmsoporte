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
        Schema::create('tickets', function (Blueprint $table) {
            $table->unsignedInteger('tick_id')->autoIncrement();
            $table->unsignedBigInteger('usu_id');
             $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->text('tick_titulo');
            $table->text('tick_descrip');
            $table->string('tick_estado');
            $table->integer('est');

            $table->foreign('usu_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
