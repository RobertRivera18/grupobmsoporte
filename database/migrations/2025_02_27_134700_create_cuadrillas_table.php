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
        Schema::create('cuadrillas', function (Blueprint $table) {
            $table->id();
            $table->string('cua_nombre', 256);
            $table->unsignedBigInteger('cua_empresa')->nullable();
            $table->unsignedBigInteger('cua_ciudad')->nullable();
            $table->tinyInteger('recargas')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuadrillas');
    }
};
