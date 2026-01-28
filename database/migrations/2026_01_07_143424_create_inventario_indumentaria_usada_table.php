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
        Schema::create('inventario_indumentaria_usada', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indumentaria_id');
            $table->unsignedBigInteger('ubicacion_id');
            $table->foreign('indumentaria_id')
                ->references('id')
                ->on('indumentarias')
                ->onDelete('cascade');

            $table->foreign('ubicacion_id')
                ->references('id')
                ->on('ubicaciones')
                ->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_indumentaria_usada');
    }
};
