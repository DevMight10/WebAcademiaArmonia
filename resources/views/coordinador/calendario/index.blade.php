@extends('layouts.coordinador')

@section('coordinador-content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Calendario de Citas</h1>
            <p class="mt-1 text-sm text-gray-600">Vista mensual de todas las citas programadas</p>
        </div>
    </div>

    {{-- Navegación de Mes --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('coordinador.calendario', ['mes' => $mes == 1 ? 12 : $mes - 1, 'anio' => $mes == 1 ? $anio - 1 : $anio]) }}" 
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                ← Anterior
            </a>
            
            <h2 class="text-2xl font-bold text-gray-900">
                {{ \Carbon\Carbon::create($anio, $mes, 1)->locale('es')->isoFormat('MMMM YYYY') }}
            </h2>
            
            <a href="{{ route('coordinador.calendario', ['mes' => $mes == 12 ? 1 : $mes + 1, 'anio' => $mes == 12 ? $anio + 1 : $anio]) }}" 
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Siguiente →
            </a>
        </div>

        {{-- Leyenda de Colores --}}
        <div class="flex gap-4 mb-4 text-sm">
            <div class="flex items-center">
                <span class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span>
                <span class="text-gray-600">Pendiente</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span>
                <span class="text-gray-600">Confirmada</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                <span class="text-gray-600">Completada</span>
            </div>
        </div>

        {{-- Calendario --}}
        <div class="grid grid-cols-7 gap-2">
            {{-- Encabezado de días --}}
            @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $dia)
                <div class="text-center font-semibold text-gray-700 py-2">{{ $dia }}</div>
            @endforeach

            @php
                $primerDia = \Carbon\Carbon::create($anio, $mes, 1);
                $ultimoDia = $primerDia->copy()->endOfMonth();
                $diasEnMes = $ultimoDia->day;
                $diaSemanaInicio = $primerDia->dayOfWeek; // 0 = Domingo
            @endphp

            {{-- Espacios vacíos antes del primer día --}}
            @for($i = 0; $i < $diaSemanaInicio; $i++)
                <div class="bg-gray-50 rounded-lg p-2 min-h-[100px]"></div>
            @endfor

            {{-- Días del mes --}}
            @for($dia = 1; $dia <= $diasEnMes; $dia++)
                @php
                    $fecha = \Carbon\Carbon::create($anio, $mes, $dia)->format('Y-m-d');
                    $citasDelDia = $citasPorDia->get($fecha, collect());
                    $esHoy = $fecha == now()->format('Y-m-d');
                @endphp
                
                <div class="bg-white border rounded-lg p-2 min-h-[100px] {{ $esHoy ? 'border-indigo-500 border-2' : 'border-gray-200' }}">
                    <div class="text-sm font-semibold text-gray-700 mb-1">{{ $dia }}</div>
                    
                    <div class="space-y-1">
                        @foreach($citasDelDia as $cita)
                            @php
                                $colorClasses = match($cita->estado) {
                                    'pendiente' => 'bg-amber-100 text-amber-800 border-amber-300',
                                    'confirmada' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    'completada' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    default => 'bg-gray-100 text-gray-800 border-gray-300',
                                };
                            @endphp
                            
                            <div class="text-xs p-1 rounded border {{ $colorClasses }}" title="{{ $cita->beneficiario->user->name }} - {{ $cita->instrumento->nombre }}">
                                <div class="font-medium truncate">{{ $cita->fecha_hora->format('H:i') }}</div>
                                <div class="truncate">{{ $cita->beneficiario->user->name }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor

            {{-- Espacios vacíos después del último día --}}
            @php
                $diasRestantes = 7 - (($diaSemanaInicio + $diasEnMes) % 7);
                if ($diasRestantes < 7) {
                    for($i = 0; $i < $diasRestantes; $i++) {
                        echo '<div class="bg-gray-50 rounded-lg p-2 min-h-[100px]"></div>';
                    }
                }
            @endphp
        </div>
    </div>

    {{-- Resumen del Mes --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen del Mes</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-amber-50 rounded-lg">
                <div class="text-2xl font-bold text-amber-700">
                    {{ $citasPorDia->flatten()->where('estado', 'pendiente')->count() }}
                </div>
                <div class="text-sm text-amber-600">Citas Pendientes</div>
            </div>
            <div class="text-center p-4 bg-emerald-50 rounded-lg">
                <div class="text-2xl font-bold text-emerald-700">
                    {{ $citasPorDia->flatten()->where('estado', 'confirmada')->count() }}
                </div>
                <div class="text-sm text-emerald-600">Citas Confirmadas</div>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-700">
                    {{ $citasPorDia->flatten()->where('estado', 'completada')->count() }}
                </div>
                <div class="text-sm text-blue-600">Citas Completadas</div>
            </div>
        </div>
    </div>
</div>
@endsection
