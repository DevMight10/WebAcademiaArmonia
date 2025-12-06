@extends('layouts.admin')

@section('title', 'Detalle del Beneficiario')

@section('admin-content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $beneficiario->user->name }}</h1>
            <p class="mt-2 text-sm text-gray-600">Información detallada del beneficiario</p>
        </div>
        <a href="{{ route('admin.beneficiarios.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Volver
        </a>
    </div>
</div>

{{-- Estadísticas --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <x-card class="bg-gradient-to-br from-indigo-50 to-indigo-100">
        <div class="text-center">
            <p class="text-sm text-indigo-600 font-medium mb-1">Total Créditos</p>
            <p class="text-4xl font-bold text-indigo-900">{{ number_format($totalCreditos) }}</p>
            <p class="text-xs text-indigo-600 mt-1">minutos</p>
        </div>
    </x-card>
    
    <x-card class="bg-gradient-to-br from-emerald-50 to-emerald-100">
        <div class="text-center">
            <p class="text-sm text-emerald-600 font-medium mb-1">Disponibles</p>
            <p class="text-4xl font-bold text-emerald-900">{{ number_format($creditosDisponibles) }}</p>
            <p class="text-xs text-emerald-600 mt-1">minutos</p>
        </div>
    </x-card>
    
    <x-card class="bg-gradient-to-br from-purple-50 to-purple-100">
        <div class="text-center">
            <p class="text-sm text-purple-600 font-medium mb-1">Consumidos</p>
            <p class="text-4xl font-bold text-purple-900">{{ number_format($creditosConsumidos) }}</p>
            <p class="text-xs text-purple-600 mt-1">minutos</p>
        </div>
    </x-card>
    
    <x-card class="bg-gradient-to-br from-blue-50 to-blue-100">
        <div class="text-center">
            <p class="text-sm text-blue-600 font-medium mb-1">Clases Completadas</p>
            <p class="text-4xl font-bold text-blue-900">{{ $totalClases }}</p>
        </div>
    </x-card>
</div>

{{-- Información Personal --}}
<x-card class="mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Información Personal</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-600">Nombre Completo</p>
            <p class="text-lg font-medium text-gray-900">{{ $beneficiario->user->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Email</p>
            <p class="text-lg font-medium text-gray-900">{{ $beneficiario->user->email }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">CI</p>
            <p class="text-lg font-medium text-gray-900">{{ $beneficiario->ci }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Teléfono</p>
            <p class="text-lg font-medium text-gray-900">{{ $beneficiario->telefono ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Fecha de Nacimiento</p>
            <p class="text-lg font-medium text-gray-900">{{ $beneficiario->fecha_nacimiento->format('d/m/Y') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Nivel Educativo</p>
            <p class="text-lg font-medium text-gray-900">{{ $beneficiario->nivel_educativo }}</p>
        </div>
    </div>
</x-card>

{{-- Distribución de Créditos --}}
<x-card class="mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Distribución de Créditos</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Compra</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asignados</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disponibles</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($beneficiario->distribuciones as $dist)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $dist->compra->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dist->compra->cliente->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($dist->minutos_asignados) }} min</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($dist->minutos_disponibles) }} min</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                @if($dist->estado == 'pendiente') bg-amber-100 text-amber-800
                                @elseif($dist->estado == 'activo') bg-emerald-100 text-emerald-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($dist->estado) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">No hay créditos asignados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>

{{-- Clases Recientes --}}
<x-card>
    <h3 class="text-lg font-bold text-gray-900 mb-4">Clases Recientes</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instrumento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duración</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($beneficiario->citas->take(10) as $cita)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cita->instrumento->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cita->instructor->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cita->duracion_minutos }} min</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                @if($cita->estado == 'pendiente') bg-amber-100 text-amber-800
                                @elseif($cita->estado == 'confirmada') bg-emerald-100 text-emerald-800
                                @elseif($cita->estado == 'completada') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">No hay clases registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
@endsection
