@extends('layouts.app')

@section('title', 'Dashboard Cliente')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Cliente</h1>
        <p class="mt-2 text-sm text-gray-600">Bienvenido, {{ auth()->user()->name }}. Gestiona tus compras y beneficiarios.</p>
    </div>

    <!-- Información Rápida -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Compras Realizadas -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Compras Realizadas</dt>
                        <dd class="text-lg font-semibold text-gray-900">--</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Créditos Comprados -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-emerald-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Créditos Comprados</dt>
                        <dd class="text-lg font-semibold text-gray-900">-- <span class="text-base font-medium text-gray-500">min</span></dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Total Invertido -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-amber-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Invertido</dt>
                        <dd class="text-lg font-semibold text-gray-900"><span class="text-base font-medium text-gray-500">Bs</span> --</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Ver Paquetes -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Comprar Créditos</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Adquiere paquetes de créditos musicales para ti o para tus beneficiarios.
                    Obtén descuentos de hasta 45%.
                </p>
                <a href="#" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Ver Paquetes Disponibles
                </a>
                <p class="text-xs text-gray-500 mt-2">RF-01.1, RF-01.2</p>
            </div>
        </div>

        <!-- Mis Compras -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Mis Compras</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Consulta el historial de todas tus compras de créditos,
                    incluyendo estados de pago y distribución de minutos.
                </p>
                <a href="#" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Ver Historial
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
