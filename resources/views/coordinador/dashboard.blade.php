@extends('layouts.coordinador')

@section('coordinador-content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Coordinador</h1>
        <p class="mt-1 text-sm text-gray-600">Resumen general de compras y citas</p>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Compras Pendientes --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-amber-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500">Compras Pendientes</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $comprasPendientes }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Citas Pendientes --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500">Citas Pendientes</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $citasPendientes }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Ingresos del Mes --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-emerald-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500">Ingresos del Mes</dt>
                        <dd class="text-lg font-semibold text-gray-900">Bs {{ number_format($ingresosDelMes, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Estudiantes Activos --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500">Estudiantes Activos</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $estudiantesActivos }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones Rápidas y Alertas --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Citas Próximas --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Próximas Citas</h3>
            </div>
            <div class="p-6">
                @forelse($citasProximas as $cita)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $cita->beneficiario->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $cita->instrumento->nombre }} - {{ $cita->instructor->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $cita->fecha_hora->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                            Confirmada
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">No hay citas próximas</p>
                @endforelse
            </div>
        </div>

        {{-- Compras Pendientes --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Compras Pendientes</h3>
            </div>
            <div class="p-6">
                @forelse($comprasPendientesRecientes as $compra)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $compra->cliente->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($compra->minutos_totales) }} minutos - Bs {{ number_format($compra->total, 2) }}</p>
                            <p class="text-xs text-gray-400">{{ $compra->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <a href="{{ route('coordinador.compras.show', $compra->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            Ver
                        </a>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">No hay compras pendientes</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Acciones Rápidas --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('coordinador.compras.index') }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <svg class="h-7 w-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-900">Gestionar Compras</h3>
                    </div>
                </div>
            </a>

            <a href="{{ route('coordinador.citas.index') }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <svg class="h-7 w-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-900">Gestionar Citas</h3>
                    </div>
                </div>
            </a>

            <a href="{{ route('coordinador.calendario') }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-900">Ver Calendario</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection