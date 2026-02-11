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
        Schema::table('informes_auditoria', function (Blueprint $table) {
            $table->text('resumen')
                ->nullable()
                ->after('auditoria_proceso_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informes_auditoria', function (Blueprint $table) {
            $table->dropColumn('resumen');
        });
    }
};
