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
        Schema::create('colaboradores', function (Blueprint $table) {
            $table->unsignedBigInteger('col_id')->autoIncrement();
            $table->string('col_nombre', 256);
            $table->string('col_cedula', 10);
            $table->unsignedBigInteger('empresa_id')->nullable()->comment('1 es CNEL, 2 es Claro');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colaboradores');
    }
};
