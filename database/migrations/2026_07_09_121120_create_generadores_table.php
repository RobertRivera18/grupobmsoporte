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
        Schema::create('generadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('capacidad_kva', 8, 2);
            $table->decimal('capacidad_kw', 8, 2);
            $table->enum('combustible', ['gasolina', 'diesel', 'gas']);
            $table->decimal('precio', 10, 2)->nullable();
            $table->string('imagen')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generadores');
    }
};
