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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained()->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['transferencia', 'qr', 'efectivo'])->default('transferencia');
            $table->string('comprobante')->nullable();
            $table->enum('estado', ['pendiente', 'verificado', 'rechazado'])->default('pendiente');
            $table->foreignId('verificado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('fecha_verificacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
