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
        Schema::create('inventario_material_control', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_control_id')->constrained('inventario_control')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materiales');
            $table->integer('stock_final')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_material_control');
    }
};
