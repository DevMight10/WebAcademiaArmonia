@extends('layouts.cliente')

@section('title', 'Confirmación de Compra')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Mensaje de Éxito -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">
                <i class="bi bi-check-circle-fill"></i> Compra Registrada Exitosamente
            </h4>
            <p>Tu solicitud de compra ha sido registrada. Espera la solicitud de pago del coordinador académico.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Detalles de la Compra -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-receipt"></i> Detalles de la Compra #{{ $compra->id }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Fecha:</strong> {{ $compra->created_at->format('d/m/Y H:i') }}</p>
                        <p class="mb-2"><strong>Estado:</strong>
                            <span class="badge bg-warning">{{ $compra->estado }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Minutos Totales:</strong> {{ $compra->minutos_totales }} min</p>
                        <p class="mb-2"><strong>Horas:</strong> {{ number_format($compra->minutos_totales / 60, 1) }} hrs</p>
                    </div>
                </div>

                <hr>

                <h6>Resumen de Pago:</h6>
                <table class="table">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-end">{{ number_format($compra->subtotal, 2) }} Bs</td>
                    </tr>
                    <tr>
                        <td>Descuento ({{ $compra->porcentaje_descuento }}%):</td>
                        <td class="text-end text-success">- {{ number_format($compra->descuento, 2) }} Bs</td>
                    </tr>
                    <tr class="table-primary">
                        <td><strong>Total a Pagar:</strong></td>
                        <td class="text-end"><strong class="fs-4">{{ number_format($compra->total, 2) }} Bs</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Distribución de Créditos -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-people-fill"></i> Distribución de Créditos
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Beneficiario</th>
                                <th>Minutos Asignados</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compra->distribuciones as $distribucion)
                                <tr>
                                    <td>
                                        <i class="bi bi-person"></i>
                                        {{ $distribucion->beneficiario->user->name }}
                                    </td>
                                    <td>{{ $distribucion->minutos_asignados }} minutos</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($distribucion->estado) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Próximos Pasos -->
        <div class="card mb-4">
            <div class="card-body">
                <h5><i class="bi bi-list-ol"></i> Próximos Pasos:</h5>
                <ol>
                    <li class="mb-2">El coordinador académico revisará tu solicitud</li>
                    <li class="mb-2">Recibirás las instrucciones de pago (transferencia, QR o efectivo)</li>
                    <li class="mb-2">Realiza el pago y envía el comprobante</li>
                    <li class="mb-2">Una vez verificado el pago, los créditos se activarán automáticamente</li>
                </ol>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('cliente.paquetes.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-box-seam"></i> Ver Más Paquetes
            </a>
            <a href="{{ route('cliente.compras.index') }}" class="btn btn-primary">
                <i class="bi bi-list-ul"></i> Ver Mis Compras
            </a>
        </div>
    </div>
</div>
@endsection
