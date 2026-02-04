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
        Schema::create('actas_firmadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuadrilla_id')
                ->constrained()
                ->onDelete('cascade');

            $table->enum('tipo', ['chip', 'equipo']);
            $table->foreignId('responsable_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('receptor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('cedula_responsable', 20);
            $table->string('cedula_receptor', 20)->nullable();
            $table->string('firma_responsable')->nullable();
            $table->string('firma_receptor')->nullable();

        
            $table->string('ruta_docx');
            $table->timestamp('firmado_en')->useCurrent();
            $table->timestamps();
            $table->index('cedula_responsable');
            $table->index('cedula_receptor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actas_firmadas');
    }
};
