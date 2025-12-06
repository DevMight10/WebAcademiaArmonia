@extends('layouts.instructor')

@section('title', 'Mis Clases')

@section('instructor-content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Mis Clases</h1>
    <p class="text-gray-600">Gestiona tu calendario de clases</p>
</div>

{{-- Filtros --}}
<x-card class="mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
            <select name="estado" class="w-full px-4 py-2 border rounded-lg">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
            <input type="date" name="fecha" value="{{ request('fecha') }}" class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div class="flex items-end">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Filtrar
            </button>
        </div>
    </form>
</x-card>

{{-- Lista de Clases --}}
@if($citas->isEmpty())
    <x-card>
        <div class="text-center py-12">
            <p class="text-gray-500">No hay clases con los filtros seleccionados</p>
        </div>
    </x-card>
@else
    <div class="grid gap-4">
        @foreach($citas as $cita)
            <x-card>
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                @if($cita->estado == 'pendiente') bg-amber-100 text-amber-800
                                @elseif($cita->estado == 'confirmada') bg-emerald-100 text-emerald-800
                                @elseif($cita->estado == 'completada') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($cita->estado) }}
                            </span>
                            <span class="text-sm font-bold text-gray-900">
                                {{ $cita->fecha_hora->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600"><strong>Estudiante:</strong> {{ $cita->beneficiario->user->name }}</p>
                                <p class="text-gray-600"><strong>Instrumento:</strong> {{ $cita->instrumento->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600"><strong>Duración:</strong> {{ $cita->duracion_minutos }} min</p>
                            </div>
                        </div>
                        
                        @if($cita->observaciones)
                            <p class="mt-2 text-sm text-gray-600"><strong>Observaciones:</strong> {{ $cita->observaciones }}</p>
                        @endif
                    </div>
                    
                    @if($cita->estado == 'confirmada')
                        <div class="ml-4">
                            <form method="POST" action="{{ route('instructor.citas.completar', $cita->id) }}">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium"
                                        onclick="return confirm('¿Marcar esta clase como completada?')">
                                    ✓ Completar Clase
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </x-card>
        @endforeach
    </div>
    
    <div class="mt-6">
        {{ $citas->links() }}
    </div>
@endif
@endsection
