@extends('layouts.admin')

@section('title', 'Reportes de Compras')

@section('admin-content')
{{-- Encabezado --}}
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        <svg class="inline-block w-8 h-8 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Reportes de Compras
    </h1>
    <p class="text-gray-600">Genera reportes en PDF y Excel de tus compras</p>
</div>

{{-- Formulario de Filtros --}}
<x-card class="mb-6">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h2 class="text-xl font-bold text-gray-800">Filtros de Búsqueda</h2>
    </div>

    <form method="GET" action="{{ route('admin.reportes.index') }}" id="form-filtros">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            {{-- Fecha Inicio --}}
            <div>
                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha Inicio
                </label>
                <input type="date" 
                       id="fecha_inicio" 
                       name="fecha_inicio"
                       value="{{ request('fecha_inicio') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            {{-- Fecha Fin --}}
            <div>
                <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha Fin
                </label>
                <input type="date" 
                       id="fecha_fin" 
                       name="fecha_fin"
                       value="{{ request('fecha_fin') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            {{-- Estado --}}
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                    Estado
                </label>
                <select id="estado" 
                        name="estado"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Todos los estados</option>
                    <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Completada" {{ request('estado') == 'Completada' ? 'selected' : '' }}>Completada</option>
                    <option value="Rechazada" {{ request('estado') == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                </select>
            </div>

            {{-- Monto Mínimo --}}
            <div>
                <label for="monto_min" class="block text-sm font-medium text-gray-700 mb-2">
                    Monto Mínimo (Bs)
                </label>
                <input type="number" 
                       id="monto_min" 
                       name="monto_min"
                       value="{{ request('monto_min') }}"
                       min="0"
                       step="0.01"
                       placeholder="0.00"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            {{-- Monto Máximo --}}
            <div>
                <label for="monto_max" class="block text-sm font-medium text-gray-700 mb-2">
                    Monto Máximo (Bs)
                </label>
                <input type="number" 
                       id="monto_max" 
                       name="monto_max"
                       value="{{ request('monto_max') }}"
                       min="0"
                       step="0.01"
                       placeholder="0.00"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" 
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition-colors">
                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Aplicar Filtros
            </button>
            <a href="{{ route('admin.reportes.index') }}" 
               class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-colors">
                Limpiar Filtros
            </a>
        </div>
    </form>
</x-card>

{{-- Estadísticas --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">Cantidad de Compras</p>
            <p class="text-4xl font-bold text-indigo-600">{{ $totalCompras }}</p>
        </div>
    </x-card>
    
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">Total Compras</p>
            <p class="text-4xl font-bold text-emerald-600">{{ number_format($totalGastado, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Bs</p>
        </div>
    </x-card>
    
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">Total Minutos</p>
            <p class="text-4xl font-bold text-amber-600">{{ $totalMinutos }}</p>
            <p class="text-xs text-gray-500 mt-1">min</p>
        </div>
    </x-card>
</div>

{{-- Botones de Exportación --}}
<x-card class="mb-6">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h2 class="text-xl font-bold text-gray-800">Exportar Datos</h2>
    </div>

    <div class="flex flex-wrap gap-3">
        {{-- Exportar a PDF --}}
        <form method="POST" action="{{ route('admin.reportes.listado.pdf') }}" class="inline-block">
            @csrf
            {{-- Pasar filtros al PDF --}}
            <input type="hidden" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
            <input type="hidden" name="fecha_fin" value="{{ request('fecha_fin') }}">
            <input type="hidden" name="estado" value="{{ request('estado') }}">
            <input type="hidden" name="monto_min" value="{{ request('monto_min') }}">
            <input type="hidden" name="monto_max" value="{{ request('monto_max') }}">
            
            <button type="submit" 
                    class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition-colors shadow-md hover:shadow-lg">
                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Exportar a PDF
            </button>
        </form>

        {{-- Exportar a Excel --}}
        <form method="POST" action="{{ route('admin.reportes.excel') }}" class="inline-block">
            @csrf
            {{-- Pasar filtros al Excel --}}
            <input type="hidden" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
            <input type="hidden" name="fecha_fin" value="{{ request('fecha_fin') }}">
            <input type="hidden" name="estado" value="{{ request('estado') }}">
            <input type="hidden" name="monto_min" value="{{ request('monto_min') }}">
            <input type="hidden" name="monto_max" value="{{ request('monto_max') }}">
            
            <button type="submit" 
                    class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-semibold transition-colors shadow-md hover:shadow-lg">
                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Exportar a Excel
            </button>
        </form>
    </div>
</x-card>

{{-- Tabla de Preview --}}
@if($compras->isNotEmpty())
    <x-card>
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h2 class="text-xl font-bold text-gray-800">Vista Previa de Datos</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Minutos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($compras as $compra)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $compra->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $compra->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $compra->minutos_totales }} min
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                {{ number_format($compra->total, 2) }} Bs
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    @if($compra->estado == 'Pendiente') bg-amber-100 text-amber-800
                                    @elseif($compra->estado == 'Completada') bg-emerald-100 text-emerald-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $compra->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('admin.reportes.compra.pdf', $compra->id) }}" 
                                   class="text-red-600 hover:text-red-900 font-semibold">
                                    PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
@else
    <x-card>
        <div class="text-center py-12">
            <svg class="mx-auto w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No se encontraron compras</h3>
            <p class="text-gray-600">Intenta ajustar los filtros de búsqueda</p>
        </div>
    </x-card>
@endif
@endsection

