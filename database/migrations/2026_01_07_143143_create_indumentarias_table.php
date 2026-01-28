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
        Schema::create('indumentarias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); 
            $table->string('tipo'); 
            $table->string('color')->nullable();
            $table->string('talla')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indumentarias');
    }
};
