@extends('layouts.beneficiario')

@section('title', 'Historial de Clases')

@section('beneficiario-content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        <svg class="inline-block w-8 h-8 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        Historial de Clases
    </h1>
    <p class="text-gray-600">Tu progreso y clases completadas</p>
</div>

{{-- Estadísticas --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">Total de Clases</p>
            <p class="text-4xl font-bold text-indigo-600">{{ $totalClases }}</p>
        </div>
    </x-card>
    
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">Minutos de Estudio</p>
            <p class="text-4xl font-bold text-emerald-600">{{ $totalMinutosUsados }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($totalMinutosUsados / 60, 1) }} horas</p>
        </div>
    </x-card>
    
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">Instrumento Favorito</p>
            @if($instrumentosFrecuentes->isNotEmpty())
                <p class="text-2xl font-bold text-purple-600">{{ $instrumentosFrecuentes->first()['instrumento']->nombre }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $instrumentosFrecuentes->first()['cantidad'] }} clases</p>
            @else
                <p class="text-gray-400">-</p>
            @endif
        </div>
    </x-card>
</div>

{{-- Progreso por Instrumento --}}
@if($instrumentosFrecuentes->isNotEmpty())
<x-card class="mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Progreso por Instrumento</h3>
    <div class="space-y-4">
        @foreach($instrumentosFrecuentes as $item)
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">{{ $item['instrumento']->nombre }}</span>
                    <span class="text-sm text-gray-500">{{ $item['cantidad'] }} clases · {{ $item['minutos'] }} min</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($item['cantidad'] / $totalClases) * 100 }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
</x-card>
@endif

{{-- Instructores Frecuentes --}}
@if($instructoresFrecuentes->isNotEmpty())
<x-card class="mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Tus Instructores</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($instructoresFrecuentes->take(3) as $item)
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-bold text-lg">{{ substr($item['instructor']->user->name, 0, 1) }}</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $item['instructor']->user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $item['cantidad'] }} clases</p>
                </div>
            </div>
        @endforeach
    </div>
</x-card>
@endif

{{-- Historial de Clases --}}
<x-card>
    <h3 class="text-lg font-bold text-gray-900 mb-4">Historial Completo</h3>
    
    @if($citasCompletadas->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Aún no has completado clases</h3>
            <p class="text-gray-600 mb-4">Tus clases completadas aparecerán aquí</p>
            <a href="{{ route('beneficiario.agendamiento.index') }}" 
               class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Agendar Primera Clase
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Observaciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($citasCompletadas as $cita)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cita->fecha_hora->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $cita->instrumento->nombre }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cita->instructor->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $cita->duracion_minutos }} min
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $cita->observaciones ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-card>
@endsection
