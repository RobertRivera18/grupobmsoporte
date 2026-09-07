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
        Schema::table('cuadrillas', function (Blueprint $table) {
            $table->foreignId('grupo_id')
                ->nullable()
                ->after('id')
                ->constrained('grupos')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuadrillas', function (Blueprint $table) {
            //
        });
    }
};
