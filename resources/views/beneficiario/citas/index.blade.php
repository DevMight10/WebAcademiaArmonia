@extends('layouts.beneficiario')

@section('title', 'Mis Citas')

@section('beneficiario-content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        <svg class="inline-block w-8 h-8 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        Mis Citas
    </h1>
    <p class="text-gray-600">Gestiona tus clases agendadas</p>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
@endif

<div class="mb-6">
    <a href="{{ route('beneficiario.agendamiento.index') }}" 
       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Agendar Nueva Clase
    </a>
</div>

@if($citas->isEmpty())
    <x-card>
        <div class="text-center py-12">
            <svg class="mx-auto w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No tienes citas agendadas</h3>
            <p class="text-gray-600 mb-4">Agenda tu primera clase para comenzar</p>
            <a href="{{ route('beneficiario.agendamiento.index') }}" 
               class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Agendar Clase
            </a>
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
                            <span class="text-sm text-gray-500">
                                {{ $cita->fecha_hora->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            {{ $cita->instrumento->nombre }}
                        </h3>
                        
                        <div class="space-y-1 text-sm text-gray-600">
                            <p><strong>Instructor:</strong> {{ $cita->instructor->user->name }}</p>
                            <p><strong>Duración:</strong> {{ $cita->duracion_minutos }} minutos</p>
                            <p><strong>Créditos consumidos:</strong> {{ $cita->minutos_consumidos }} minutos</p>
                            @if($cita->observaciones)
                                <p><strong>Observaciones:</strong> {{ $cita->observaciones }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($cita->puedeCancelarse())
                        <form method="POST" action="{{ route('beneficiario.citas.cancelar', $cita->id) }}" 
                              onsubmit="return confirm('¿Estás seguro de cancelar esta cita? Tus créditos serán devueltos.')">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-sm font-medium">
                                Cancelar
                            </button>
                        </form>
                    @endif
                </div>
            </x-card>
        @endforeach
    </div>
@endif
@endsection
