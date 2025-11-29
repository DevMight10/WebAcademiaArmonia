@extends('layouts.cliente')

@section('title', 'Confirmación de Compra')

@section('cliente-content')
{{-- Mensaje de Éxito --}}
@if(session('success'))
    <x-alert type="success" class="mb-6">
        <div class="flex">
            <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-bold text-lg mb-1">¡Compra Registrada Exitosamente!</h4>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </x-alert>
@endif

{{-- Encabezado --}}
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        <svg class="inline-block w-8 h-8 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Confirmación de Compra
    </h1>
    <p class="text-gray-600">Detalles completos de tu compra</p>
</div>

<div class="max-w-4xl mx-auto space-y-6">
    {{-- Detalles de la Compra --}}
    <x-card>
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Detalles de la Compra #{{ $compra->id }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="space-y-3">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Fecha</p>
                        <p class="font-semibold text-gray-900">{{ $compra->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Estado</p>
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                            @if($compra->estado == 'Pendiente') bg-amber-100 text-amber-800
                            @elseif($compra->estado == 'Pago Solicitado') bg-blue-100 text-blue-800
                            @elseif($compra->estado == 'Completada') bg-emerald-100 text-emerald-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $compra->estado }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Minutos Totales</p>
                        <p class="font-semibold text-gray-900">{{ $compra->minutos_totales }} min</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Horas</p>
                        <p class="font-semibold text-gray-900">{{ number_format($compra->minutos_totales / 60, 1) }} hrs</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <h6 class="font-bold text-gray-800 mb-4">Resumen de Pago:</h6>
            <div class="bg-indigo-50 rounded-xl p-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">Subtotal:</span>
                        <span class="font-semibold text-gray-900">{{ number_format($compra->subtotal, 2) }} Bs</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">Descuento ({{ $compra->porcentaje_descuento }}%):</span>
                        <span class="font-semibold text-emerald-600">- {{ number_format($compra->descuento, 2) }} Bs</span>
                    </div>
                    <div class="border-t border-indigo-200 pt-3 mt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total a Pagar:</span>
                            <span class="text-3xl font-bold text-indigo-600">
                                {{ number_format($compra->total, 2) }} Bs
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card>

    {{-- Distribución de Créditos --}}
    <x-card>
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Distribución de Créditos
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Beneficiario
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Minutos Asignados
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($compra->distribuciones as $distribucion)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="font-medium text-gray-900">
                                        {{ $distribucion->beneficiario->user->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $distribucion->minutos_asignados }} minutos
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($distribucion->estado) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Próximos Pasos --}}
    <x-card>
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Próximos Pasos
            </h2>
        </div>

        <ol class="space-y-4">
            <li class="flex items-start">
                <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-sm mr-4">
                    1
                </span>
                <div class="flex-1 pt-1">
                    <p class="text-gray-700">El coordinador académico revisará tu solicitud</p>
                </div>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-sm mr-4">
                    2
                </span>
                <div class="flex-1 pt-1">
                    <p class="text-gray-700">Recibirás las instrucciones de pago (transferencia, QR o efectivo)</p>
                </div>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-sm mr-4">
                    3
                </span>
                <div class="flex-1 pt-1">
                    <p class="text-gray-700">Realiza el pago y envía el comprobante</p>
                </div>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-sm mr-4">
                    4
                </span>
                <div class="flex-1 pt-1">
                    <p class="text-gray-700">Una vez verificado el pago, los créditos se activarán automáticamente</p>
                </div>
            </li>
        </ol>
    </x-card>

    {{-- Botones de Acción --}}
    <div class="flex flex-col sm:flex-row justify-between gap-4">
        <a href="{{ route('cliente.paquetes.index') }}"
           class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            Ver Más Paquetes
        </a>
        <a href="{{ route('cliente.compras.index') }}"
           class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Ver Mis Compras
        </a>
    </div>
</div>
@endsection
