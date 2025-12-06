@extends('layouts.beneficiario')

@section('title', 'Dashboard Beneficiario')

@section('beneficiario-content')
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Beneficiario</h1>
        <p class="mt-2 text-sm text-gray-600">Bienvenido, {{ auth()->user()->name }}</p>
    </div>

    @php
        $beneficiario = auth()->user()->beneficiario;
        $totalDisponible = 0;
        $totalConsumido = 0;
        
        if ($beneficiario) {
            $totalDisponible = $beneficiario->distribuciones->sum('minutos_disponibles');
            $totalConsumido = $beneficiario->distribuciones->sum('minutos_consumidos');
        }
        
        $clasesEquivalentes = $totalDisponible > 0 ? floor($totalDisponible / 60) : 0;
    @endphp

    {{-- Créditos Disponibles (Destacado) --}}
    <div class="bg-slate-800 rounded-lg shadow-lg p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-300 text-sm font-medium">Tus Créditos Disponibles</p>
                <p class="text-5xl font-bold mt-2">{{ $totalDisponible }} minutos</p>
                <p class="text-slate-300 text-sm mt-2">Equivalente a {{ $clasesEquivalentes }} clases de 60 minutos</p>
            </div>
            <div class="hidden md:block">
                <svg class="h-24 w-24 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Clases Tomadas --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Clases Tomadas</dt>
                        <dd class="text-lg font-semibold text-gray-900">0</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Créditos Consumidos --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Créditos Consumidos</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $totalConsumido }} min</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Próxima Clase --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Próxima Clase</dt>
                        <dd class="text-sm font-semibold text-gray-900">No agendada</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones Principales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Consultar Saldo --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Mis Créditos</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Consulta el saldo detallado de tus créditos disponibles
                    y el historial de consumo en tus clases musicales.
                </p>
                <a href="{{ route('beneficiario.creditos.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Ver Detalle de Créditos
                </a>
            </div>
        </div>

        {{-- Agendar Clase --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Agendar Clase</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Agenda una nueva clase musical. Elige tu instrumento,
                    instructor, fecha y hora preferida.
                </p>
                <a href="#" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nueva Clase
                </a>
                <p class="text-xs text-gray-500 mt-2">RF-03.1 (Próximamente)</p>
            </div>
        </div>
    </div>
@endsection
