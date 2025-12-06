@extends('layouts.coordinador')

@section('title', 'Gestión de Compras')

@section('coordinador-content')
    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <svg class="inline-block w-8 h-8 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Gestión de Compras
        </h1>
        <p class="text-gray-600">Revisa y aprueba las solicitudes de compra de créditos</p>
    </div>

    {{-- Estado vacío --}}
    @if($compras->isEmpty())
        <x-card class="max-w-2xl mx-auto">
            <div class="text-center py-12">
                <svg class="mx-auto w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No hay compras pendientes</h3>
                <p class="text-gray-600">Todas las compras han sido procesadas</p>
            </div>
        </x-card>
    @else
        {{-- Tabla de compras pendientes --}}
        <x-card>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minutos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beneficiarios</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($compras as $compra)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $compra->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $compra->cliente->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $compra->cliente->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $compra->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $compra->minutos_totales }} min
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-indigo-600">{{ number_format($compra->total, 2) }} Bs</div>
                                    @if($compra->porcentaje_descuento > 0)
                                        <div class="text-xs text-emerald-600">{{ $compra->porcentaje_descuento }}% desc.</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $compra->distribuciones->count() }} personas
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="verDetalles({{ $compra->id }})" 
                                            class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                        Revisar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif

{{-- Modal de Revisión --}}
<div id="modal-revision" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0" onclick="cerrarModal()"></div>
    
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl p-6 max-w-3xl w-full shadow-2xl ring-1 ring-gray-900/10">
            <h4 class="text-xl font-bold mb-6 text-gray-900">Detalles de la Compra</h4>
            
            <div id="contenido-modal" class="space-y-6">
                {{-- Se llena dinámicamente con JavaScript --}}
            </div>

            <div id="error-modal" class="hidden bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                <p class="text-red-600 text-sm"></p>
            </div>

            <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" onclick="cerrarModal()" 
                        class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
                    Cancelar
                </button>
                <button type="button" onclick="rechazarCompra()" 
                        class="flex-1 px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 font-semibold transition-colors">
                    Rechazar Compra
                </button>
                <button type="button" onclick="aprobarCompra()" 
                        class="flex-1 px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-semibold transition-colors">
                    Aprobar Compra
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let compraActual = null;

async function verDetalles(compraId) {
    compraActual = compraId;
    
    try {
        const response = await fetch(`/coordinador/compras/${compraId}`);
        const data = await response.json();
        
        const compra = data.compra;
        
        // Generar HTML con los detalles
        const contenido = `
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h5 class="font-semibold text-gray-900 mb-3">Información de la Compra</h5>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm text-gray-500">ID de Compra</dt>
                            <dd class="text-sm font-medium text-gray-900">#${compra.id}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Fecha</dt>
                            <dd class="text-sm font-medium text-gray-900">${compra.fecha}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Minutos Totales</dt>
                            <dd class="text-sm font-medium text-gray-900">${compra.minutos_totales} min</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Descuento</dt>
                            <dd class="text-sm font-medium text-emerald-600">${compra.porcentaje_descuento}%</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Total a Pagar</dt>
                            <dd class="text-2xl font-bold text-indigo-600">${parseFloat(compra.total).toFixed(2)} Bs</dd>
                        </div>
                    </dl>
                </div>
                
                <div>
                    <h5 class="font-semibold text-gray-900 mb-3">Datos del Cliente</h5>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm text-gray-500">Nombre</dt>
                            <dd class="text-sm font-medium text-gray-900">${compra.cliente.nombre}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="text-sm font-medium text-gray-900">${compra.cliente.email}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">CI</dt>
                            <dd class="text-sm font-medium text-gray-900">${compra.cliente.ci}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Teléfono</dt>
                            <dd class="text-sm font-medium text-gray-900">${compra.cliente.telefono}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <div class="mt-6">
                <h5 class="font-semibold text-gray-900 mb-3">Distribución de Créditos</h5>
                <div class="bg-gray-50 rounded-xl p-4">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left text-xs font-medium text-gray-500 uppercase pb-2">Beneficiario</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase pb-2">Email</th>
                                <th class="text-right text-xs font-medium text-gray-500 uppercase pb-2">Minutos</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            ${compra.distribuciones.map(dist => `
                                <tr>
                                    <td class="py-2 text-sm font-medium text-gray-900">${dist.beneficiario}</td>
                                    <td class="py-2 text-sm text-gray-500">${dist.email}</td>
                                    <td class="py-2 text-sm text-right font-semibold text-indigo-600">${dist.minutos_asignados} min</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
        
        document.getElementById('contenido-modal').innerHTML = contenido;
        document.getElementById('modal-revision').classList.remove('hidden');
        
    } catch (error) {
        console.error('Error al cargar detalles:', error);
        alert('Error al cargar los detalles de la compra');
    }
}

function cerrarModal() {
    document.getElementById('modal-revision').classList.add('hidden');
    document.getElementById('error-modal').classList.add('hidden');
    compraActual = null;
}

async function aprobarCompra() {
    if (!compraActual) return;
    
    if (!confirm('¿Estás seguro de aprobar esta compra? Los créditos quedarán disponibles para el cliente.')) {
        return;
    }
    
    try {
        const response = await fetch(`/coordinador/compras/${compraActual}/aprobar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            alert('Compra aprobada exitosamente');
            window.location.reload();
        } else {
            mostrarError(data.message || 'Error al aprobar la compra');
        }
    } catch (error) {
        mostrarError('Error de conexión. Intenta de nuevo.');
    }
}

async function rechazarCompra() {
    if (!compraActual) return;
    
    const motivo = prompt('Ingresa el motivo del rechazo (mínimo 10 caracteres):');
    
    if (!motivo || motivo.length < 10) {
        alert('El motivo debe tener al menos 10 caracteres');
        return;
    }
    
    try {
        const response = await fetch(`/coordinador/compras/${compraActual}/rechazar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ motivo })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            alert('Compra rechazada');
            window.location.reload();
        } else {
            mostrarError(data.message || 'Error al rechazar la compra');
        }
    } catch (error) {
        mostrarError('Error de conexión. Intenta de nuevo.');
    }
}

function mostrarError(mensaje) {
    const errorDiv = document.getElementById('error-modal');
    errorDiv.querySelector('p').textContent = mensaje;
    errorDiv.classList.remove('hidden');
}
</script>
@endpush
@endsection
