@extends('layouts.cliente')

@section('title', 'Historial de Compras')

@section('cliente-content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Historial de Compras</h1>
    <p class="text-gray-600">Todas tus compras de créditos</p>
</div>

{{-- Estadísticas Resumen --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <x-card class="bg-gradient-to-br from-indigo-50 to-indigo-100">
        <div class="text-center">
            <p class="text-sm text-indigo-600 font-medium mb-1">Total de Compras</p>
            <p class="text-4xl font-bold text-indigo-900">{{ $compras->total() }}</p>
        </div>
    </x-card>
    
    <x-card class="bg-gradient-to-br from-emerald-50 to-emerald-100">
        <div class="text-center">
            <p class="text-sm text-emerald-600 font-medium mb-1">Total Invertido</p>
            <p class="text-4xl font-bold text-emerald-900">${{ number_format($compras->sum('total'), 2) }}</p>
        </div>
    </x-card>
    
    <x-card class="bg-gradient-to-br from-purple-50 to-purple-100">
        <div class="text-center">
            <p class="text-sm text-purple-600 font-medium mb-1">Minutos Comprados</p>
            <p class="text-4xl font-bold text-purple-900">{{ number_format($compras->sum('minutos_totales')) }}</p>
            <p class="text-xs text-purple-600 mt-1">{{ number_format($compras->sum('minutos_totales') / 60, 1) }} horas</p>
        </div>
    </x-card>
</div>

{{-- Tabla de Compras --}}
<x-card>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Todas las Compras</h3>
    </div>
    
    @if($compras->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No hay compras registradas</h3>
            <p class="text-gray-600 mb-4">Realiza tu primera compra de créditos</p>
            <a href="{{ route('cliente.compras.create') }}" 
               class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Nueva Compra
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Minutos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Beneficiarios</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($compras as $compra)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $compra->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $compra->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($compra->minutos_totales) }} min
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                ${{ number_format($compra->total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    @if($compra->estado == 'pendiente') bg-amber-100 text-amber-800
                                    @elseif($compra->estado == 'aprobada') bg-emerald-100 text-emerald-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($compra->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $compra->distribuciones->count() }} beneficiarios
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="mostrarDetalles({{ $compra->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    Ver detalles
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $compras->links() }}
        </div>
    @endif
</x-card>

{{-- Modal de Detalles (sin fondo oscuro) --}}
<div id="modalDetalles" class="hidden fixed inset-0 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border-2 border-gray-300 w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-lg bg-white">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-4 pb-3 border-b">
            <h3 class="text-xl font-bold text-gray-900">Detalles de la Compra</h3>
            <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Contenido del Modal --}}
        <div id="contenidoModal">
            {{-- Se llenará dinámicamente con JavaScript --}}
        </div>
    </div>
</div>

@push('scripts')
<script>
// Datos de compras para el modal
const comprasData = {
    @foreach($compras as $compra)
        {{ $compra->id }}: {
            id: {{ $compra->id }},
            fecha: '{{ $compra->created_at->format('d/m/Y H:i') }}',
            minutos: '{{ number_format($compra->minutos_totales) }}',
            total: '{{ number_format($compra->total, 2) }}',
            estado: '{{ ucfirst($compra->estado) }}',
            distribuciones: [
                @foreach($compra->distribuciones as $dist)
                {
                    beneficiario: '{{ $dist->beneficiario->user->name }}',
                    email: '{{ $dist->beneficiario->user->email }}',
                    minutos: {{ $dist->minutos_asignados }},
                    disponibles: {{ $dist->minutos_disponibles }},
                    consumidos: {{ $dist->minutos_asignados - $dist->minutos_disponibles }},
                    esCliente: {{ $dist->beneficiario->user_id == auth()->user()->id ? 'true' : 'false' }}
                },
                @endforeach
            ]
        },
    @endforeach
};

// Mostrar modal con detalles
function mostrarDetalles(compraId) {
    const compra = comprasData[compraId];
    if (!compra) return;
    
    // Generar HTML del contenido
    let html = `
        <div class="space-y-4">
            <!-- Información General -->
            <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm text-gray-600">ID de Compra</p>
                    <p class="text-lg font-bold text-gray-900">#${compra.id}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Fecha</p>
                    <p class="text-lg font-bold text-gray-900">${compra.fecha}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Minutos</p>
                    <p class="text-lg font-bold text-indigo-600">${compra.minutos} min</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Pagado</p>
                    <p class="text-lg font-bold text-emerald-600">$${compra.total}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-600">Estado</p>
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full ${
                        compra.estado === 'Pendiente' ? 'bg-amber-100 text-amber-800' :
                        compra.estado === 'Aprobada' ? 'bg-emerald-100 text-emerald-800' :
                        'bg-red-100 text-red-800'
                    }">${compra.estado}</span>
                </div>
            </div>

            <!-- Distribución de Créditos -->
            <div>
                <h4 class="font-bold text-gray-900 mb-3">Distribución de Créditos</h4>
                <div class="space-y-3">
    `;
    
    compra.distribuciones.forEach((dist, index) => {
        const porcentajeConsumido = ((dist.consumidos / dist.minutos) * 100).toFixed(1);
        const esCliente = dist.esCliente || false;
        
        html += `
            <div class="p-3 border rounded-lg ${esCliente ? 'bg-violet-50 border-violet-300' : ''}">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <p class="font-medium text-gray-900">${dist.beneficiario}</p>
                            ${esCliente ? '<span class="inline-block text-xs bg-violet-600 text-white px-2 py-0.5 rounded-full font-semibold">Tú (Cliente)</span>' : ''}
                        </div>
                        <p class="text-xs text-gray-500">${dist.email}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">${dist.minutos} min</p>
                        <p class="text-xs text-gray-500">${dist.disponibles} disponibles</p>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full" style="width: ${porcentajeConsumido}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">${porcentajeConsumido}% consumido</p>
            </div>
        `;
    });
    
    html += `
                </div>
            </div>
        </div>
    `;
    
    // Insertar contenido y mostrar modal
    document.getElementById('contenidoModal').innerHTML = html;
    document.getElementById('modalDetalles').classList.remove('hidden');
}

// Cerrar modal
function cerrarModal() {
    document.getElementById('modalDetalles').classList.add('hidden');
}
</script>
@endpush
@endsection
