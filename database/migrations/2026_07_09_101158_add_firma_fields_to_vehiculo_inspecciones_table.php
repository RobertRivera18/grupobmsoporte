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
        Schema::table('vehiculo_inspecciones', function (Blueprint $table) {
            $table->string('firma_path')->nullable()->after('recomendaciones_mantenimiento');
            $table->string('documento_firmado_path')->nullable()->after('firma_path');
            $table->timestamp('firmado_at')->nullable()->after('documento_firmado_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehiculo_inspecciones', function (Blueprint $table) {
            //
        });
    }
};
