@extends('layouts.instructor')

@section('title', 'Dashboard Instructor')

@section('instructor-content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Instructor</h1>
    <parameter name="p" class="text-gray-600">Bienvenido, {{ auth()->user()->name }}</p>
</div>

{{-- Estadísticas del Mes --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">Clases Este Mes</p>
            <p class="text-4xl font-bold text-indigo-600">{{ $totalClasesEsteMes }}</p>
        </div>
    </x-card>
    
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">Horas Impartidas</p>
            <p class="text-4xl font-bold text-emerald-600">{{ number_format($horasEsteMes, 1) }}</p>
        </div>
    </x-card>
    
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">Pendientes Confirmar</p>
            <p class="text-4xl font-bold text-amber-600">{{ $pendientesConfirmar }}</p>
        </div>
    </x-card>
</div>

{{-- Clases de Hoy --}}
<x-card class="mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Clases de Hoy</h3>
    
    @if($citasHoy->isEmpty())
        <div class="text-center py-8">
            <svg class="mx-auto w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-500">No tienes clases programadas para hoy</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($citasHoy as $cita)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-lg font-bold text-gray-900">{{ $cita->fecha_hora->format('H:i') }}</span>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($cita->estado == 'pendiente') bg-amber-100 text-amber-800
                                @else bg-emerald-100 text-emerald-800
                                @endif">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-700">
                            <strong>{{ $cita->beneficiario->user->name }}</strong> · {{ $cita->instrumento->nombre }} · {{ $cita->duracion_minutos }} min
                        </p>
                        @if($cita->observaciones)
                            <p class="text-xs text-gray-500 mt-1">{{ $cita->observaciones }}</p>
                        @endif
                    </div>
                    
                    @if($cita->estado == 'confirmada')
                        <form method="POST" action="{{ route('instructor.citas.completar', $cita->id) }}" class="ml-4">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium"
                                    onclick="return confirm('¿Marcar esta clase como completada?')">
                                ✓ Completar
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-card>

{{-- Próximas Clases --}}
<x-card>
    <h3 class="text-lg font-bold text-gray-900 mb-4">Próximas Clases (7 días)</h3>
    
    @if($proximasCitas->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500">No tienes clases programadas próximamente</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha y Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instrumento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duración</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($proximasCitas as $cita)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cita->fecha_hora->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cita->beneficiario->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $cita->instrumento->nombre }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $cita->duracion_minutos }} min
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 text-center">
            <a href="{{ route('instructor.citas.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                Ver todas mis clases →
            </a>
        </div>
    @endif
</x-card>
@endsection
