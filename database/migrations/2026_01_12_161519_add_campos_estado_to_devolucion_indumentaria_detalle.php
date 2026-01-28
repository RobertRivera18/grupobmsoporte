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
        Schema::table('devolucion_indumentaria_detalle', function (Blueprint $table) {
            $table->unsignedInteger('cantidad_reutilizable')
                ->default(0)
                ->after('cantidad');

            // Cantidad que se da de baja
            $table->unsignedInteger('cantidad_baja')
                ->default(0)
                ->after('cantidad_reutilizable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devolucion_indumentaria_detalle', function (Blueprint $table) {
            //
        });
    }
};
