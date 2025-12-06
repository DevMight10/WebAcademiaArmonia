@extends('layouts.admin')

@section('title', 'Detalle del Cliente')

@section('admin-content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $cliente->user->name }}</h1>
            <p class="mt-2 text-sm text-gray-600">Información detallada del cliente</p>
        </div>
        <a href="{{ route('admin.clientes.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Volver
        </a>
    </div>
</div>

{{-- Estadísticas --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <x-card class="bg-gradient-to-br from-indigo-50 to-indigo-100">
        <div class="text-center">
            <p class="text-sm text-indigo-600 font-medium mb-1">Total Compras</p>
            <p class="text-4xl font-bold text-indigo-900">{{ $totalCompras }}</p>
        </div>
    </x-card>
    
    <x-card class="bg-gradient-to-br from-emerald-50 to-emerald-100">
        <div class="text-center">
            <p class="text-sm text-emerald-600 font-medium mb-1">Total Invertido</p>
            <p class="text-4xl font-bold text-emerald-900">${{ number_format($totalInvertido, 2) }}</p>
        </div>
    </x-card>
    
    <x-card class="bg-gradient-to-br from-purple-50 to-purple-100">
        <div class="text-center">
            <p class="text-sm text-purple-600 font-medium mb-1">Minutos Comprados</p>
            <p class="text-4xl font-bold text-purple-900">{{ number_format($minutosComprados) }}</p>
        </div>
    </x-card>
</div>

{{-- Información Personal --}}
<x-card class="mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Información Personal</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-600">Nombre Completo</p>
            <p class="text-lg font-medium text-gray-900">{{ $cliente->user->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Email</p>
            <p class="text-lg font-medium text-gray-900">{{ $cliente->user->email }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">CI</p>
            <p class="text-lg font-medium text-gray-900">{{ $cliente->ci }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Teléfono</p>
            <p class="text-lg font-medium text-gray-900">{{ $cliente->telefono ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Fecha de Registro</p>
            <p class="text-lg font-medium text-gray-900">{{ $cliente->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</x-card>

{{-- Compras Recientes --}}
<x-card>
    <h3 class="text-lg font-bold text-gray-900 mb-4">Compras Recientes</h3>
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
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($cliente->compras->take(10) as $compra)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $compra->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $compra->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($compra->minutos_totales) }} min</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${{ number_format($compra->total, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                @if($compra->estado == 'pendiente') bg-amber-100 text-amber-800
                                @elseif($compra->estado == 'aprobada') bg-emerald-100 text-emerald-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($compra->estado) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $compra->distribuciones->count() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No hay compras registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
@endsection
