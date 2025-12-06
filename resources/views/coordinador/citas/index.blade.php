@extends('layouts.coordinador')

@section('title', 'Gestión de Citas')

@section('coordinador-content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestión de Citas</h1>
    <p class="text-gray-600">Administra las citas agendadas por los beneficiarios</p>
</div>

{{-- Estadísticas --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">Citas Pendientes</p>
            <p class="text-4xl font-bold text-amber-600">{{ $pendientes }}</p>
        </div>
    </x-card>
    <x-card>
        <div class="text-center">
            <p class="text-sm text-gray-500 mb-2">Citas Confirmadas</p>
            <p class="text-4xl font-bold text-emerald-600">{{ $confirmadas }}</p>
        </div>
    </x-card>
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

{{-- Lista de Citas --}}
@if($citas->isEmpty())
    <x-card>
        <div class="text-center py-12">
            <p class="text-gray-500">No hay citas con los filtros seleccionados</p>
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
                                <p class="text-gray-600"><strong>Beneficiario:</strong> {{ $cita->beneficiario->user->name }}</p>
                                <p class="text-gray-600"><strong>Instrumento:</strong> {{ $cita->instrumento->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600"><strong>Instructor:</strong> {{ $cita->instructor->user->name }}</p>
                                <p class="text-gray-600"><strong>Duración:</strong> {{ $cita->duracion_minutos }} min</p>
                            </div>
                        </div>
                        
                        @if($cita->observaciones)
                            <p class="mt-2 text-sm text-gray-600"><strong>Observaciones:</strong> {{ $cita->observaciones }}</p>
                        @endif
                    </div>
                    
                    <div class="flex gap-2 ml-4">
                        @if($cita->estado == 'pendiente')
                            <form method="POST" action="{{ route('coordinador.citas.confirmar', $cita->id) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm">
                                    Confirmar
                                </button>
                            </form>
                            <button onclick="rechazarCita({{ $cita->id }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                                Rechazar
                            </button>
                        @elseif($cita->estado == 'confirmada')
                            <form method="POST" action="{{ route('coordinador.citas.completar', $cita->id) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                    Completar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>
    
    <div class="mt-6">
        {{ $citas->links() }}
    </div>
@endif

{{-- Modal Rechazar --}}
<div id="modal-rechazar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-bold mb-4">Rechazar Cita</h3>
        <form id="form-rechazar" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo del rechazo</label>
                <textarea name="motivo" required rows="3" class="w-full px-4 py-2 border rounded-lg" placeholder="Explica por qué se rechaza esta cita..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Rechazar Cita
                </button>
                <button type="button" onclick="cerrarModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function rechazarCita(citaId) {
    const modal = document.getElementById('modal-rechazar');
    const form = document.getElementById('form-rechazar');
    form.action = `/coordinador/citas/${citaId}/rechazar`;
    modal.classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-rechazar').classList.add('hidden');
}
</script>
@endpush
@endsection
