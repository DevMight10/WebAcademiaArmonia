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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('beneficiario_id')->constrained('beneficiarios')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('instructores')->onDelete('cascade');
            $table->foreignId('instrumento_id')->constrained('instrumentos')->onDelete('cascade');
            $table->foreignId('distribucion_credito_id')->constrained('distribucion_creditos')->onDelete('cascade');
            
            // Datos de la cita
            $table->dateTime('fecha_hora');
            $table->integer('duracion_minutos'); // 30, 45, 60, 90, 120
            $table->integer('minutos_consumidos'); // Calculado al crear
            
            // Estado de la cita
            $table->enum('estado', ['pendiente', 'confirmada', 'completada', 'cancelada'])->default('pendiente');
            
            // Información adicional
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
            
            // Índices para consultas frecuentes
            $table->index(['beneficiario_id', 'estado']);
            $table->index(['instructor_id', 'fecha_hora']);
            $table->index('fecha_hora');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
