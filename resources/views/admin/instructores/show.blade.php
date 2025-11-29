@extends('layouts.admin')

@section('title', 'Detalle del Instructor')

@section('admin-content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.instructores.index') }}"
               class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $instructore->user->name }}</h1>
                <p class="mt-1 text-sm text-gray-600">Información del instructor</p>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.instructores.edit', $instructore) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
        </div>
    </div>

    <!-- Estado Badge -->
    <div>
        @if($instructore->estado)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Instructor Activo
            </span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                Instructor Inactivo
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información Personal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Datos Personales -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Datos Personales</h2>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instructore->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Carnet de Identidad</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instructore->ci }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Correo Electrónico</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instructore->user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instructore->telefono ?? 'No registrado' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Especialidades -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Especialidades</h2>
                </div>
                <div class="px-6 py-5">
                    @if($instructore->especialidades->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($instructore->especialidades as $especialidad)
                                @php
                                    $instrumento = $especialidad->instrumento;
                                    $categoriaInst = App\Enums\CategoriaInstrumento::from($instrumento->categoria);
                                    $colorClasses = match($categoriaInst) {
                                        App\Enums\CategoriaInstrumento::BASICO => 'bg-green-50 text-green-700 border-green-200',
                                        App\Enums\CategoriaInstrumento::INTERMEDIO => 'bg-blue-50 text-blue-700 border-blue-200',
                                        App\Enums\CategoriaInstrumento::AVANZADO => 'bg-purple-50 text-purple-700 border-purple-200',
                                        App\Enums\CategoriaInstrumento::ESPECIALIZADO => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                @endphp
                                <div class="border-2 rounded-lg p-3 {{ $colorClasses }}">
                                    <div class="font-medium">{{ $instrumento->nombre }}</div>
                                    <div class="text-xs mt-1">{{ $categoriaInst->label() }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No tiene especialidades asignadas</p>
                    @endif
                </div>
            </div>

            <!-- Horarios Disponibles (opcional, por si en el futuro se implementa) -->
            @if($instructore->horarios->count() > 0)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Horarios Disponibles</h2>
                    </div>
                    <div class="px-6 py-5">
                        <div class="space-y-2">
                            @foreach($instructore->horarios as $horario)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                    <span class="text-sm font-medium text-gray-900">{{ ucfirst($horario->dia_semana) }}</span>
                                    <span class="text-sm text-gray-600">
                                        {{ $horario->hora_inicio }} - {{ $horario->hora_fin }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar con información profesional -->
        <div class="space-y-6">
            <!-- Categoría y Factor de Costo -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Información Profesional</h2>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">Categoría</dt>
                        @php
                            $colorClasses = match($categoria) {
                                App\Enums\CategoriaInstructor::REGULAR => 'bg-blue-100 text-blue-800 border-blue-200',
                                App\Enums\CategoriaInstructor::PREMIUM => 'bg-purple-100 text-purple-800 border-purple-200',
                                App\Enums\CategoriaInstructor::INVITADO => 'bg-amber-100 text-amber-800 border-amber-200',
                            };
                        @endphp
                        <dd class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border-2 {{ $colorClasses }}">
                            {{ $categoria->label() }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Factor de Costo</dt>
                        <dd class="mt-1 text-2xl font-bold text-indigo-600">{{ number_format($instructore->factor_costo, 2) }}x</dd>
                        <p class="text-xs text-gray-500 mt-1">Multiplicador aplicado al costo de clases</p>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $instructore->created_at->format('d/m/Y') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $instructore->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </div>
            </div>

            <!-- Estadísticas (placeholder para futuras funcionalidades) -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Estadísticas</h2>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Especialidades</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $instructore->especialidades->count() }}</dd>
                    </div>
                    <div class="opacity-50">
                        <dt class="text-sm font-medium text-gray-500">Clases Impartidas</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">-</dd>
                        <p class="text-xs text-gray-500 mt-1">Disponible próximamente</p>
                    </div>
                    <div class="opacity-50">
                        <dt class="text-sm font-medium text-gray-500">Estudiantes</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">-</dd>
                        <p class="text-xs text-gray-500 mt-1">Disponible próximamente</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones -->
    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
        <a href="{{ route('admin.instructores.index') }}"
           class="text-gray-600 hover:text-gray-900 font-medium">
            ← Volver al listado
        </a>

        <div class="flex gap-3">
            @if($instructore->estado)
                <form action="{{ route('admin.instructores.destroy', $instructore) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow transition"
                            onclick="return confirm('¿Estás seguro de desactivar este instructor?')">
                        Desactivar Instructor
                    </button>
                </form>
            @else
                <form action="{{ route('admin.instructores.restore', $instructore) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">
                        Activar Instructor
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
