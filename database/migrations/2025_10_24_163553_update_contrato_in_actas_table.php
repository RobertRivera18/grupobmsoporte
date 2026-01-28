<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('actas', function (Blueprint $table) {
            // Eliminamos el campo ENUM anterior
            $table->dropColumn('contrato');

            // Creamos la relación con tipo_contratos
            $table->foreignId('tipo_contrato_id')
                ->nullable()
                ->constrained('tipo_contratos')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('actas', function (Blueprint $table) {
            $table->dropForeign(['tipo_contrato_id']);
            $table->dropColumn('tipo_contrato_id');
            $table->enum('contrato', ['039', '045', '029'])->nullable();
        });
    }
};
