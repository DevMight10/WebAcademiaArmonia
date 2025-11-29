@extends('layouts.cliente')

@section('title', 'Comprar Créditos')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">
                    <i class="bi bi-cart-plus"></i> Nueva Compra de Créditos
                </h2>

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Versión Simplificada</strong><br>
                    Esta es una versión simple del formulario. La versión final multi-paso se implementará próximamente.
                </div>

                <form action="{{ route('cliente.compras.store') }}" method="POST">
                    @csrf

                    <!-- Paso 1: Cantidad de Minutos -->
                    <div class="mb-4">
                        <h5><i class="bi bi-1-circle-fill text-primary"></i> Cantidad de Minutos</h5>
                        <hr>

                        <div class="mb-3">
                            <label for="minutos_totales" class="form-label">
                                Minutos a Comprar <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   class="form-control form-control-lg"
                                   id="minutos_totales"
                                   name="minutos_totales"
                                   min="30"
                                   step="10"
                                   value="{{ old('minutos_totales', request('minutos', 300)) }}"
                                   required>
                            <small class="text-muted">Mínimo 30 minutos</small>
                        </div>

                        <div id="precio_preview" class="alert alert-info">
                            <strong>Precio estimado:</strong> Ingresa la cantidad para ver el total
                        </div>
                    </div>

                    <!-- Nota: Funcionalidad Simplificada -->
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Nota para Desarrollo</h6>
                        <p class="mb-0">
                            Por ahora, esta compra se asignará automáticamente al primer cliente y beneficiario en la base de datos.
                            <br><strong>Asegúrate de tener al menos 1 cliente y 1 beneficiario creados.</strong>
                        </p>
                    </div>

                    <!-- Hidden fields temporales (se reemplazarán con el multi-paso real) -->
                    <input type="hidden" name="beneficiarios[0][user_id]" value="1">
                    <input type="hidden" id="distribucion_0" name="distribuciones[0]" value="300">

                    <!-- Botones -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('cliente.paquetes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver a Paquetes
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Confirmar Compra
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('minutos_totales').addEventListener('input', async function() {
    const minutos = this.value;
    const distribucion = document.getElementById('distribucion_0');

    // Actualizar distribución con el total
    distribucion.value = minutos;

    if (minutos >= 30) {
        try {
            const response = await fetch('{{ route("cliente.paquetes.calcular") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ minutos: parseInt(minutos) })
            });

            const data = await response.json();

            if (response.ok) {
                document.getElementById('precio_preview').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Subtotal:</strong> ${data.subtotal.toLocaleString('es-BO', {minimumFractionDigits: 2})} Bs</p>
                            <p class="mb-1"><strong>Descuento:</strong> ${data.porcentaje_descuento}%</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Ahorro:</strong> ${data.monto_descuento.toLocaleString('es-BO', {minimumFractionDigits: 2})} Bs</p>
                            <p class="mb-0"><strong class="fs-4">Total:</strong> <span class="fs-4 text-primary">${data.total.toLocaleString('es-BO', {minimumFractionDigits: 2})} Bs</span></p>
                        </div>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error calculando precio:', error);
        }
    }
});

// Trigger inicial
document.getElementById('minutos_totales').dispatchEvent(new Event('input'));
</script>
@endpush
