@extends('layouts.cliente')

@section('title', 'Paquetes de Créditos')

@push('styles')
<style>
    .package-card {
        position: relative;
        overflow: hidden;
    }

    .package-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
    }

    .popular-badge {
        position: absolute;
        top: 20px;
        right: -35px;
        background: #f59e0b;
        color: white;
        padding: 5px 40px;
        transform: rotate(45deg);
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 2px 10px rgba(245, 158, 11, 0.3);
    }

    .price-original {
        text-decoration: line-through;
        color: #9ca3af;
        font-size: 1rem;
    }

    .price-final {
        font-size: 2.5rem;
        font-weight: 700;
        color: #6366f1;
    }

    .discount-badge {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 700;
        display: inline-block;
        margin: 0.5rem 0;
    }

    .feature-list {
        list-style: none;
        padding: 0;
    }

    .feature-list li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .feature-list li:last-child {
        border-bottom: none;
    }

    .feature-list i {
        color: #10b981;
        margin-right: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center text-white">
        <h1 class="display-4 fw-bold mb-2">
            <i class="bi bi-music-note-list"></i> Paquetes de Créditos Musicales
        </h1>
        <p class="lead">Compra créditos y aprende a tu ritmo. Cuanto más compras, más ahorras!</p>
    </div>
</div>

<!-- Paquetes Predefinidos -->
<div class="row g-4 mb-5">
    @foreach($paquetes as $index => $paquete)
        <div class="col-lg-4 col-md-6">
            <div class="card package-card h-100">
                @if($paquete['minutos'] == 1500)
                    <span class="popular-badge">POPULAR</span>
                @endif

                <div class="card-body d-flex flex-column">
                    <!-- Nombre del Paquete -->
                    <div class="text-center mb-3">
                        <h3 class="card-title fw-bold text-uppercase">
                            {{ App\Services\PrecioService::obtenerNombrePaquete($paquete['minutos']) }}
                        </h3>
                        <p class="text-muted mb-1">
                            <i class="bi bi-clock"></i> {{ $paquete['minutos'] }} minutos
                            <small>({{ number_format($paquete['horas'], 1) }} horas)</small>
                        </p>
                    </div>

                    <!-- Descuento -->
                    @if($paquete['porcentaje_descuento'] > 0)
                        <div class="text-center">
                            <span class="discount-badge">
                                <i class="bi bi-tag-fill"></i> {{ $paquete['porcentaje_descuento'] }}% Descuento
                            </span>
                        </div>
                    @endif

                    <!-- Precios -->
                    <div class="text-center my-4">
                        @if($paquete['porcentaje_descuento'] > 0)
                            <div class="price-original">
                                {{ number_format($paquete['subtotal'], 2) }} Bs
                            </div>
                        @endif
                        <div class="price-final">
                            {{ number_format($paquete['total'], 2) }} <small class="fs-5">Bs</small>
                        </div>
                        @if($paquete['porcentaje_descuento'] > 0)
                            <div class="text-success fw-bold">
                                Ahorras: {{ number_format($paquete['ahorro'], 2) }} Bs
                            </div>
                        @endif
                    </div>

                    <!-- Características -->
                    <ul class="feature-list mb-4 flex-grow-1">
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <strong>{{ $paquete['minutos'] }}</strong> minutos de clase
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Precio: <strong>{{ number_format($paquete['total'] / $paquete['minutos'], 2) }} Bs/min</strong>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Distribuye entre <strong>hasta 4 beneficiarios</strong>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Sin fecha de expiración
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Todos los instrumentos disponibles
                        </li>
                    </ul>

                    <!-- Botón de Compra -->
                    <div class="text-center mt-auto">
                        <a href="{{ route('cliente.compras.create', ['minutos' => $paquete['minutos']]) }}"
                           class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-cart-plus"></i> Comprar Ahora
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Sección de Compra Personalizada -->
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4">
                    <i class="bi bi-pencil-square"></i> Paquete Personalizado
                </h3>
                <p class="text-center text-muted mb-4">
                    ¿Necesitas una cantidad específica? Calcula tu paquete personalizado (mínimo 30 minutos)
                </p>

                <form id="calcularForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="minutos_personalizados" class="form-label">Cantidad de Minutos</label>
                            <input type="number"
                                   class="form-control form-control-lg"
                                   id="minutos_personalizados"
                                   min="30"
                                   step="10"
                                   placeholder="Ej: 450">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-calculator"></i> Calcular Precio
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Resultado del cálculo -->
                <div id="resultado" class="mt-4" style="display: none;">
                    <div class="alert alert-info">
                        <h5 class="alert-heading">Tu Paquete Personalizado:</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Minutos:</strong> <span id="res_minutos"></span></p>
                                <p class="mb-1"><strong>Subtotal:</strong> <span id="res_subtotal"></span> Bs</p>
                                <p class="mb-1"><strong>Descuento:</strong> <span id="res_descuento"></span>%</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Ahorro:</strong> <span id="res_ahorro"></span> Bs</p>
                                <p class="mb-1"><strong class="fs-4">Total:</strong> <span id="res_total" class="fs-4 text-primary"></span> Bs</p>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" id="btn_comprar_personalizado" class="btn btn-success btn-lg">
                                <i class="bi bi-cart-check"></i> Comprar Este Paquete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('calcularForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const minutos = document.getElementById('minutos_personalizados').value;

    if (minutos < 30) {
        alert('La cantidad mínima es 30 minutos');
        return;
    }

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
            document.getElementById('res_minutos').textContent = data.minutos;
            document.getElementById('res_subtotal').textContent = data.subtotal.toLocaleString('es-BO', {minimumFractionDigits: 2});
            document.getElementById('res_descuento').textContent = data.porcentaje_descuento;
            document.getElementById('res_ahorro').textContent = data.monto_descuento.toLocaleString('es-BO', {minimumFractionDigits: 2});
            document.getElementById('res_total').textContent = data.total.toLocaleString('es-BO', {minimumFractionDigits: 2});

            const btnComprar = document.getElementById('btn_comprar_personalizado');
            btnComprar.href = '{{ route("cliente.compras.create") }}?minutos=' + data.minutos;

            document.getElementById('resultado').style.display = 'block';
        } else {
            alert('Error al calcular el precio');
        }
    } catch (error) {
        alert('Error de conexión');
        console.error(error);
    }
});
</script>
@endpush
