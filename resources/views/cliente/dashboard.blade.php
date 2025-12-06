@extends('layouts.cliente')

@section('title', 'Dashboard')

@section('cliente-content')
{{-- Header --}}
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
    <p class="text-gray-600">Bienvenido, {{ auth()->user()->name }}</p>
</div>

{{-- Estadísticas Principales --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    {{-- Total Gastado --}}
    <x-card class="bg-gradient-to-br from-indigo-50 to-indigo-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-indigo-600 font-medium mb-1">Total Invertido</p>
                <p class="text-3xl font-bold text-indigo-900">${{ number_format($totalGastado, 2) }}</p>
                <p class="text-xs text-indigo-600 mt-1">{{ $totalCompras }} compras</p>
            </div>
            <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </x-card>

    {{-- Minutos Disponibles --}}
    <x-card class="bg-gradient-to-br from-emerald-50 to-emerald-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-emerald-600 font-medium mb-1">Créditos Disponibles</p>
                <p class="text-3xl font-bold text-emerald-900">{{ $totalMinutosDisponibles }}</p>
                <p class="text-xs text-emerald-600 mt-1">{{ number_format($totalMinutosDisponibles / 60, 1) }} horas</p>
            </div>
            <svg class="w-12 h-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </x-card>

    {{-- Minutos Consumidos --}}
    <x-card class="bg-gradient-to-br from-purple-50 to-purple-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-purple-600 font-medium mb-1">Créditos Usados</p>
                <p class="text-3xl font-bold text-purple-900">{{ $totalMinutosConsumidos }}</p>
                <p class="text-xs text-purple-600 mt-1">{{ number_format(($totalMinutosConsumidos / max($totalMinutosComprados, 1)) * 100, 1) }}% del total</p>
            </div>
            <svg class="w-12 h-12 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
    </x-card>

    {{-- Beneficiarios Activos --}}
    <x-card class="bg-gradient-to-br from-amber-50 to-amber-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-amber-600 font-medium mb-1">Beneficiarios Activos</p>
                <p class="text-3xl font-bold text-amber-900">{{ $beneficiariosActivos }}</p>
                <p class="text-xs text-amber-600 mt-1">con créditos</p>
            </div>
            <svg class="w-12 h-12 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
    </x-card>
</div>

{{-- Gráfico de Consumo Mensual --}}
<x-card class="mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Consumo de Créditos (Últimos 6 Meses)</h3>
    <div class="h-64">
        <canvas id="consumoChart"></canvas>
    </div>
</x-card>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Progreso por Beneficiario --}}
    <x-card>
        <h3 class="text-lg font-bold text-gray-900 mb-4">Progreso de Beneficiarios</h3>
        @if($progresoBeneficiarios->isEmpty())
            <p class="text-gray-500 text-center py-8">No hay beneficiarios registrados</p>
        @else
            <div class="space-y-4">
                @foreach($progresoBeneficiarios as $progreso)
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">{{ $progreso->beneficiario->user->name }}</span>
                            <span class="text-sm text-gray-500">{{ $progreso->disponibles }} / {{ $progreso->total }} min</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-indigo-600 h-3 rounded-full transition-all" 
                                 style="width: {{ ($progreso->consumidos / max($progreso->total, 1)) * 100 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format(($progreso->consumidos / max($progreso->total, 1)) * 100, 1) }}% consumido
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>

    {{-- Próximas Clases --}}
    <x-card>
        <h3 class="text-lg font-bold text-gray-900 mb-4">Próximas Clases</h3>
        @if($proximasClases->isEmpty())
            <p class="text-gray-500 text-center py-8">No hay clases programadas</p>
        @else
            <div class="space-y-3">
                @foreach($proximasClases as $cita)
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-bold text-gray-900">{{ $cita->fecha_hora->format('d/m/Y H:i') }}</span>
                                <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full
                                    @if($cita->estado == 'pendiente') bg-amber-100 text-amber-800
                                    @else bg-emerald-100 text-emerald-800
                                    @endif">
                                    {{ ucfirst($cita->estado) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600">
                                <strong>{{ $cita->beneficiario->user->name }}</strong> · 
                                {{ $cita->instrumento->nombre }} · 
                                {{ $cita->instructor->user->name }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>
</div>

{{-- Compras Recientes --}}
<x-card>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Compras Recientes</h3>
        <a href="{{ route('cliente.compras.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
            Ver todas →
        </a>
    </div>
    @if($comprasRecientes->isEmpty())
        <p class="text-gray-500 text-center py-8">No hay compras registradas</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Minutos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($comprasRecientes as $compra)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $compra->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $compra->minutos_totales }} min
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ${{ number_format($compra->total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                    @if($compra->estado == 'pendiente') bg-amber-100 text-amber-800
                                    @elseif($compra->estado == 'aprobada') bg-emerald-100 text-emerald-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($compra->estado) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-card>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de consumo mensual
const ctx = document.getElementById('consumoChart').getContext('2d');
const consumoData = @json($consumoMensual);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: consumoData.map(d => d.mes),
        datasets: [{
            label: 'Minutos Consumidos',
            data: consumoData.map(d => d.minutos),
            backgroundColor: 'rgba(99, 102, 241, 0.8)',
            borderColor: 'rgba(99, 102, 241, 1)',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value + ' min';
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection
