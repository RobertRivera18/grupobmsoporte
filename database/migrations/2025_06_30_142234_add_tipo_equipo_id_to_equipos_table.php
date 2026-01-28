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
        // CORREGIDO
        Schema::table('equipos', function (Blueprint $table) {
            $table->foreignId('tipo_equipo_id')
                ->nullable()
                ->constrained('tipo_equipos')
                ->onDelete('set null');
        });
    }

    /**F
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            //
        });
    }
};
