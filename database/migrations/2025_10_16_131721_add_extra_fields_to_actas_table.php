<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('actas', function (Blueprint $table) {
            $table->enum('empresa', ['Claro', 'CNEL'])->nullable()->after('user_id');
            $table->string('tipo_sangre', 5)->nullable()->after('empresa');
            $table->enum('contrato', ['039', '045', '029'])->nullable()->after('tipo_sangre');
            $table->string('imagen_path')->nullable()->after('ruta_firma');
        });
    }

    public function down(): void
    {
        Schema::table('actas', function (Blueprint $table) {
            $table->dropColumn(['empresa', 'tipo_sangre', 'contrato', 'imagen_path']);
        });
    }
};
