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
        Schema::table('indicadores', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indicadores', function (Blueprint $table) {
            $table->dropForeign(['frecuencia_id']);
            $table->dropColumn('frecuencia_id');

            // Opcional: volver a dejar frecuencia como texto
            $table->string('frecuencia')->nullable();
        });
    }
};
