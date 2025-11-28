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
        Schema::create('instructor_especialidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('instructores')->onDelete('cascade');
            $table->foreignId('instrumento_id')->constrained('instrumentos')->onDelete('cascade');
            $table->timestamps();

            // Evitar duplicados
            $table->unique(['instructor_id', 'instrumento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_especialidades');
    }
};
