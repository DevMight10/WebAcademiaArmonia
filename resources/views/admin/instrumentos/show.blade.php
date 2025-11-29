@extends('layouts.admin')

@section('title', 'Detalle del Instrumento')

@section('admin-content')
<div class="max-w-4xl">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $instrumento->nombre }}</h1>
            <p class="mt-1 text-sm text-gray-600">Información detallada del instrumento</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.instrumentos.edit', $instrumento) }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            <a href="{{ route('admin.instrumentos.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information Card -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información Principal -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Información Principal</h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Nombre del Instrumento</dt>
                        <dd class="flex items-center">
                            <svg class="w-5 h-5 text-indigo-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"></path>
                            </svg>
                            <span class="text-lg font-semibold text-gray-900">{{ $instrumento->nombre }}</span>
                        </dd>
                    </div>

                    <!-- Categoría -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Categoría</dt>
                        <dd>
                            @php
                                $badgeColors = [
                                    'basico' => 'bg-green-100 text-green-800',
                                    'intermedio' => 'bg-blue-100 text-blue-800',
                                    'avanzado' => 'bg-yellow-100 text-yellow-800',
                                    'especializado' => 'bg-purple-100 text-purple-800',
                                ];
                            @endphp
                            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-lg {{ $badgeColors[$instrumento->categoria] }}">
                                {{ $categoria->label() }}
                            </span>
                        </dd>
                    </div>

                    <!-- Factor de Costo -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Factor de Costo</dt>
                        <dd class="text-2xl font-bold text-indigo-600">{{ $instrumento->factor_costo }}x</dd>
                        <p class="text-xs text-gray-500 mt-1">
                            Multiplicador aplicado al consumo de créditos
                        </p>
                    </div>

                    <!-- Estado -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Estado</dt>
                        <dd>
                            @if($instrumento->estado)
                                <span class="px-4 py-2 inline-flex items-center text-sm font-semibold rounded-lg bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Activo
                                </span>
                            @else
                                <span class="px-4 py-2 inline-flex items-center text-sm font-semibold rounded-lg bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Inactivo
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Información de Uso -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Cálculo de Consumo de Créditos</h2>

                <div class="space-y-4">
                    <p class="text-sm text-gray-600">
                        Este instrumento tiene un <strong>factor de costo de {{ $instrumento->factor_costo }}x</strong>, lo que significa que:
                    </p>

                    <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-indigo-900 mb-2">Ejemplo de cálculo:</h4>
                        <div class="text-sm text-indigo-700 space-y-2">
                            <p><strong>Fórmula:</strong> Créditos = Duración × Factor Instrumento × Factor Modalidad × Factor Instructor</p>

                            <div class="mt-3 space-y-1">
                                <p>📊 <strong>Clase individual (60 min) con instructor regular:</strong></p>
                                <p class="ml-4">60 min × {{ $instrumento->factor_costo }} × 1.0 × 1.0 = <strong>{{ 60 * $instrumento->factor_costo }} créditos</strong></p>

                                <p class="mt-2">📊 <strong>Clase dúo (60 min) con instructor premium:</strong></p>
                                <p class="ml-4">60 min × {{ $instrumento->factor_costo }} × 0.75 × 1.2 = <strong>{{ 60 * $instrumento->factor_costo * 0.75 * 1.2 }} créditos</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Información del Sistema -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Información del Registro</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500">Creado</dt>
                        <dd class="text-gray-900 font-medium">{{ $instrumento->created_at->format('d/m/Y') }}</dd>
                        <dd class="text-gray-500 text-xs">{{ $instrumento->created_at->format('H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Última actualización</dt>
                        <dd class="text-gray-900 font-medium">{{ $instrumento->updated_at->format('d/m/Y') }}</dd>
                        <dd class="text-gray-500 text-xs">{{ $instrumento->updated_at->format('H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Hace</dt>
                        <dd class="text-gray-900 font-medium">{{ $instrumento->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Acciones -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Acciones</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.instrumentos.edit', $instrumento) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Instrumento
                    </a>

                    @if($instrumento->estado)
                        <form action="{{ route('admin.instrumentos.destroy', $instrumento) }}"
                              method="POST"
                              onsubmit="return confirm('¿Estás seguro de desactivar este instrumento?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                                Desactivar Instrumento
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.instrumentos.restore', $instrumento) }}"
                              method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Activar Instrumento
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
