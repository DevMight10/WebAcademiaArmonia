@extends('layouts.cliente')

@section('title', 'Mis Clases')

@section('cliente-content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Mis Clases</h1>
    <p class="text-gray-600">Historial de todas tus clases</p>
</div>

{{-- Estadísticas --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <x-card class="bg-gradient-to-br from-indigo-50 to-indigo-100">
        <div class="text-center">
            <p class="text-sm text-indigo-600 font-medium mb-1">Total de Clases</p>
            <p class="text-4xl font-bold text-indigo-900">{{ $totalClases }}</p>
        </div>
    </x-card>
    
    <x-card class="bg-gradient-to-br from-emerald-50 to-emerald-100">
        <div class="text-center">
            <p class="text-sm text-emerald-600 font-medium mb-1">Minutos de Estudio</p>
            <p class="text-4xl font-bold text-emerald-900">{{ $totalMinutosUsados }}</p>
            <p class="text-xs text-emerald-600 mt-1">{{ number_format($totalMinutosUsados / 60, 1) }} horas</p>
        </div>
    </x-card>
    
    <x-card class="bg-gradient-to-br from-purple-50 to-purple-100">
        <div class="text-center">
            <p class="text-sm text-purple-600 font-medium mb-1">Instrumento Favorito</p>
            <p class="text-2xl font-bold text-purple-900">
                {{ $instrumentosFrecuentes->first()['instrumento'] ?? 'N/A' }}
            </p>
        </div>
    </x-card>
</div>

{{-- Lista de Clases --}}
<x-card>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Todas las Clases</h3>
        <a href="{{ route('cliente.agendamiento.index') }}" 
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            + Nueva Clase
        </a>
    </div>
    
    @if($citas->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No hay clases registradas</h3>
            <p class="text-gray-600 mb-4">Agenda tu primera clase</p>
            <a href="{{ route('cliente.agendamiento.index') }}" 
               class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Agendar Clase
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instrumento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duración</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($citas as $cita)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cita->fecha_hora->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cita->instrumento->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cita->instructor->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cita->duracion_minutos }} min
                            </td>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($cita->estado == 'pendiente')
                                    <form method="POST" action="{{ route('cliente.clases.cancelar', $cita->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('¿Cancelar esta clase?')"
                                                class="text-red-600 hover:text-red-900 font-medium">
                                            Cancelar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $citas->links() }}
        </div>
    @endif
</x-card>

@if(session('success'))
    <script>
        setTimeout(() => {
            const alert = document.querySelector('.bg-green-100');
            if (alert) alert.remove();
        }, 5000);
    </script>
@endif
@endsection
