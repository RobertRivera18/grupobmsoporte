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
        Schema::create('tickectdetalle', function (Blueprint $table) {
            $table->unsignedBigInteger('tickd_id')->autoIncrement();
            $table->unsignedInteger('tick_id');
            $table->unsignedBigInteger('usu_id');
            $table->string('tickd_descrip');
            $table->integer('est')->default(1);
            $table->foreign('tick_id')->references('tick_id')->on('tickets')->onDelete('cascade');
            $table->foreign('usu_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickectdetalle');
    }
};
