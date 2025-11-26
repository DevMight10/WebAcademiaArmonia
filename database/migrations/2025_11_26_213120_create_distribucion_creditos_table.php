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
        Schema::create('distribucion_creditos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained()->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained()->onDelete('cascade');
            $table->integer('minutos_asignados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribucion_creditos');
    }
};
