@extends('layouts.cliente')

@section('title', 'Mis Compras')

@section('cliente-content')
{{-- Encabezado con botón de acción --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <svg class="inline-block w-8 h-8 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Mis Compras
        </h1>
        <p class="text-gray-600">Historial de todas tus compras de créditos musicales</p>
    </div>
    <a href="{{ route('cliente.compras.create') }}"
       class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Nueva Compra
    </a>
</div>

{{-- Estado vacío: No hay compras --}}
@if($compras->isEmpty())
    <x-card class="max-w-2xl mx-auto">
        <div class="text-center py-12">
            <svg class="mx-auto w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No tienes compras registradas</h3>
            <p class="text-gray-600 mb-6">Comienza comprando tus primeros créditos musicales</p>
            <a href="{{ route('cliente.paquetes.index') }}"
               class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Ver Paquetes Disponibles
            </a>
        </div>
    </x-card>
@else
    {{-- Grid de Compras --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($compras as $compra)
            <x-card class="h-full flex flex-col">
                {{-- Encabezado de la tarjeta --}}
                <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-bold text-gray-800">Compra #{{ $compra->id }}</span>
                    </div>
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                        @if($compra->estado == 'Pendiente') bg-amber-100 text-amber-800
                        @elseif($compra->estado == 'Pago Solicitado') bg-blue-100 text-blue-800
                        @elseif($compra->estado == 'Completada') bg-emerald-100 text-emerald-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $compra->estado }}
                    </span>
                </div>

                {{-- Información de la compra --}}
                <div class="space-y-3 mb-4 flex-grow">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Fecha</p>
                            <p class="font-medium text-gray-900">{{ $compra->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Minutos</p>
                            <p class="font-medium text-gray-900">
                                {{ $compra->minutos_totales }} min
                                <span class="text-gray-500 text-sm">({{ number_format($compra->minutos_totales / 60, 1) }} hrs)</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Total</p>
                            <p class="text-2xl font-bold text-indigo-600">
                                {{ number_format($compra->total, 2) }} Bs
                            </p>
                        </div>
                    </div>

                    @if($compra->porcentaje_descuento > 0)
                        <div>
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                {{ $compra->porcentaje_descuento }}% Descuento Aplicado
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Beneficiarios --}}
                <div class="border-t border-gray-200 pt-4 mb-4">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-semibold text-gray-800 text-sm">Beneficiarios:</span>
                    </div>
                    <ul class="space-y-1 ml-7">
                        @foreach($compra->distribuciones as $dist)
                            <li class="flex items-center text-sm text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium">{{ $dist->beneficiario->user->name ?? 'N/A' }}</span>
                                <span class="text-gray-500 text-xs ml-2">({{ $dist->minutos_asignados }} min)</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Información de pago (si existe) --}}
                @if($compra->pago)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 mr-2 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <div class="text-xs">
                                <p class="font-semibold text-blue-800">Pago: {{ $compra->pago->metodo_pago }}</p>
                                <p class="text-blue-700">Estado: {{ $compra->pago->estado }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Botón de acción --}}
                <div class="mt-auto">
                    <a href="{{ route('cliente.compras.confirmacion', $compra->id) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Ver Detalles
                    </a>
                </div>
            </x-card>
        @endforeach
    </div>
@endif
@endsection
