@extends('layouts.beneficiario')

@section('title', 'Mis Créditos')

@section('beneficiario-content')
    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <svg class="inline-block w-8 h-8 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Mis Créditos
        </h1>
        <p class="text-gray-600">Consulta el detalle de tus créditos musicales</p>
    </div>

    {{-- Resumen de Créditos --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Total Asignado --}}
        <x-card>
            <div class="text-center">
                <p class="text-sm text-gray-500 mb-2">Total Asignado</p>
                <p class="text-4xl font-bold text-gray-900">{{ $totalAsignado }}</p>
                <p class="text-sm text-gray-500 mt-1">minutos</p>
            </div>
        </x-card>

        {{-- Disponible --}}
        <x-card class="bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="text-center">
                <p class="text-sm text-emerald-700 mb-2 font-semibold">Disponible</p>
                <p class="text-4xl font-bold text-emerald-600">{{ $totalDisponible }}</p>
                <p class="text-sm text-emerald-600 mt-1">minutos</p>
            </div>
        </x-card>

        {{-- Consumido --}}
        <x-card>
            <div class="text-center">
                <p class="text-sm text-gray-500 mb-2">Consumido</p>
                <p class="text-4xl font-bold text-amber-600">{{ $totalConsumido }}</p>
                <p class="text-sm text-gray-500 mt-1">minutos</p>
            </div>
        </x-card>
    </div>

    {{-- Estado vacío --}}
    @if($distribuciones->isEmpty())
        <x-card class="max-w-2xl mx-auto">
            <div class="text-center py-12">
                <svg class="mx-auto w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No tienes créditos asignados</h3>
                <p class="text-gray-600">Solicita a un cliente que te agregue como beneficiario en una compra</p>
            </div>
        </x-card>
    @else
        {{-- Tabla de Créditos --}}
        <x-card>
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h2 class="text-xl font-bold text-gray-800">Detalle de Créditos por Compra</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compra</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Asignados</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Disponibles</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Consumidos</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($distribuciones as $dist)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $dist->compra_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $dist->compra->cliente->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $dist->compra->cliente->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $dist->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-semibold text-gray-900">{{ $dist->minutos_asignados }}</span>
                                    <span class="text-xs text-gray-500">min</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-emerald-600">{{ $dist->minutos_disponibles }}</span>
                                    <span class="text-xs text-emerald-600">min</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-semibold text-amber-600">{{ $dist->minutos_consumidos }}</span>
                                    <span class="text-xs text-amber-600">min</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                        @if($dist->estado == 'activo') bg-emerald-100 text-emerald-800
                                        @elseif($dist->estado == 'pendiente') bg-amber-100 text-amber-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($dist->estado) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        {{-- Información Adicional --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Cómo usar tus créditos --}}
            <x-card>
                <div class="border-b border-gray-200 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-800">💡 Cómo usar tus créditos</h3>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Usa tus minutos disponibles para agendar clases musicales</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Cada clase consume los minutos de su duración</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Los créditos pendientes se activan cuando el coordinador aprueba la compra</span>
                    </li>
                </ul>
            </x-card>

            {{-- Estadísticas --}}
            <x-card>
                <div class="border-b border-gray-200 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-800">📊 Estadísticas</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Compras recibidas:</span>
                        <span class="text-sm font-bold text-gray-900">{{ $distribuciones->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Porcentaje usado:</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ $totalAsignado > 0 ? round(($totalConsumido / $totalAsignado) * 100, 1) : 0 }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Clases equivalentes:</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ $totalDisponible > 0 ? floor($totalDisponible / 60) : 0 }} clases de 60 min
                        </span>
                    </div>
                </div>
            </x-card>
        </div>
    @endif
@endsection
