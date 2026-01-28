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
        Schema::create('ingresos_indumentaria', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('indumentaria_id');
            $table->unsignedBigInteger('ubicacion_id');
            $table->unsignedBigInteger('user_id');

            $table->integer('cantidad');
            $table->text('observacion')->nullable();

            $table->timestamps();

            // 🔑 FKs CORRECTAS
            $table->foreign('indumentaria_id')
                ->references('id')
                ->on('indumentarias')
                ->onDelete('cascade');

            $table->foreign('ubicacion_id')
                ->references('id')
                ->on('ubicaciones') // ✅ CORRECTO
                ->onDelete('restrict');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresos_indumentaria');
    }
};
