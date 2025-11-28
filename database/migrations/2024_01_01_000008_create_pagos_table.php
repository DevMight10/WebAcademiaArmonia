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
            $table->foreignId('compra_id')->constrained('compras')->onDelete('cascade');
            $table->string('metodo_pago');
            $table->decimal('monto', 10, 2);
            $table->string('comprobante')->nullable();
            $table->timestamp('fecha_solicitud');
            $table->timestamp('fecha_verificacion')->nullable();
            $table->foreignId('verificado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->string('estado')->default('pendiente');
            $table->text('notas')->nullable();
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
