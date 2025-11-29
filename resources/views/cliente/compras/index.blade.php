@extends('layouts.cliente')

@section('title', 'Mis Compras')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>
                <i class="bi bi-cart3"></i> Mis Compras
            </h2>
            <a href="{{ route('cliente.compras.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Compra
            </a>
        </div>
    </div>
</div>

@if($compras->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 4rem; color: #cbd5e1;"></i>
            <h4 class="mt-3 text-muted">No tienes compras registradas</h4>
            <p class="text-muted">Comienza comprando tus primeros créditos musicales</p>
            <a href="{{ route('cliente.paquetes.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-box-seam"></i> Ver Paquetes Disponibles
            </a>
        </div>
    </div>
@else
    <div class="row">
        @foreach($compras as $compra)
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>
                            <i class="bi bi-receipt"></i> Compra #{{ $compra->id }}
                        </span>
                        <span class="badge
                            @if($compra->estado == 'Pendiente') bg-warning
                            @elseif($compra->estado == 'Pago Solicitado') bg-info
                            @elseif($compra->estado == 'Completada') bg-success
                            @else bg-secondary
                            @endif
                        ">
                            {{ $compra->estado }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="mb-1">
                                <strong>Fecha:</strong>
                                {{ $compra->created_at->format('d/m/Y H:i') }}
                            </p>
                            <p class="mb-1">
                                <strong>Minutos:</strong>
                                {{ $compra->minutos_totales }} min
                                ({{ number_format($compra->minutos_totales / 60, 1) }} hrs)
                            </p>
                            <p class="mb-1">
                                <strong>Total:</strong>
                                <span class="fs-5 text-primary fw-bold">
                                    {{ number_format($compra->total, 2) }} Bs
                                </span>
                            </p>
                            @if($compra->porcentaje_descuento > 0)
                                <p class="mb-1">
                                    <span class="badge bg-success">
                                        {{ $compra->porcentaje_descuento }}% Descuento Aplicado
                                    </span>
                                </p>
                            @endif
                        </div>

                        <hr>

                        <div class="mb-3">
                            <strong><i class="bi bi-people"></i> Beneficiarios:</strong>
                            <ul class="list-unstyled mt-2 ms-3">
                                @foreach($compra->distribuciones as $dist)
                                    <li class="mb-1">
                                        <i class="bi bi-person-fill text-primary"></i>
                                        {{ $dist->beneficiario->user->name ?? 'N/A' }}
                                        <small class="text-muted">({{ $dist->minutos_asignados }} min)</small>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @if($compra->pago)
                            <div class="alert alert-info mb-0">
                                <small>
                                    <i class="bi bi-credit-card"></i>
                                    <strong>Pago:</strong> {{ $compra->pago->metodo_pago }}
                                    <br>
                                    <strong>Estado:</strong> {{ $compra->pago->estado }}
                                </small>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('cliente.compras.confirmacion', $compra->id) }}"
                           class="btn btn-sm btn-outline-primary w-100">
                            <i class="bi bi-eye"></i> Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
