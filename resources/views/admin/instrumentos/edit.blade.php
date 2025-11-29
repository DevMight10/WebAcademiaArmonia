@extends('layouts.admin')

@section('title', 'Editar Instrumento')

@section('admin-content')
<div class="max-w-3xl">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Instrumento</h1>
        <p class="mt-1 text-sm text-gray-600">Actualiza la información del instrumento "{{ $instrumento->nombre }}"</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('admin.instrumentos.update', $instrumento) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre del Instrumento <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nombre"
                       id="nombre"
                       value="{{ old('nombre', $instrumento->nombre) }}"
                       required
                       placeholder="Ej: Piano, Guitarra, Violín..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('nombre') border-red-500 @enderror">
                @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categoría -->
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">
                    Categoría <span class="text-red-500">*</span>
                </label>
                <select name="categoria"
                        id="categoria"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('categoria') border-red-500 @enderror">
                    <option value="">Selecciona una categoría</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->value }}"
                                data-factor="{{ $cat->factorCosto() }}"
                                {{ old('categoria', $instrumento->categoria) == $cat->value ? 'selected' : '' }}>
                            {{ $cat->label() }} (Factor: {{ $cat->factorCosto() }}x)
                        </option>
                    @endforeach
                </select>
                @error('categoria')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Información de categorías -->
                <div class="mt-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2">Información de Categorías:</h4>
                    <ul class="text-xs text-blue-700 space-y-1">
                        <li><strong>Básico (1.0x):</strong> Guitarra, Ukelele</li>
                        <li><strong>Intermedio (1.0x):</strong> Piano, Violín, Flauta</li>
                        <li><strong>Avanzado (1.15x):</strong> Saxofón, Batería, Canto lírico</li>
                        <li><strong>Especializado (1.25x):</strong> Arpa, Violonchelo, Trompeta</li>
                    </ul>
                </div>
            </div>

            <!-- Factor de Costo (readonly) -->
            <div>
                <label for="factor_costo_display" class="block text-sm font-medium text-gray-700 mb-1">
                    Factor de Costo
                </label>
                <input type="text"
                       id="factor_costo_display"
                       readonly
                       value="{{ $instrumento->factor_costo }}x"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                <p class="mt-1 text-xs text-gray-500">
                    Este valor se actualiza automáticamente según la categoría seleccionada
                </p>
            </div>

            <!-- Estado -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox"
                           name="estado"
                           id="estado"
                           value="1"
                           {{ old('estado', $instrumento->estado) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Instrumento activo</span>
                </label>
                <p class="mt-1 ml-6 text-xs text-gray-500">
                    Los instrumentos activos están disponibles para su uso en el sistema
                </p>
            </div>

            <!-- Información adicional -->
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Información del registro:</h4>
                <div class="text-xs text-gray-600 space-y-1">
                    <p><strong>Creado:</strong> {{ $instrumento->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Última actualización:</strong> {{ $instrumento->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition">
                    Actualizar Instrumento
                </button>
                <a href="{{ route('admin.instrumentos.show', $instrumento) }}"
                   class="flex-1 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg text-center transition">
                    Ver Detalles
                </a>
                <a href="{{ route('admin.instrumentos.index') }}"
                   class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg text-center transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Script para actualizar el factor de costo automáticamente -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoriaSelect = document.getElementById('categoria');
    const factorCostoDisplay = document.getElementById('factor_costo_display');

    // Función para actualizar el factor de costo
    function updateFactorCosto() {
        const selectedOption = categoriaSelect.options[categoriaSelect.selectedIndex];
        const factor = selectedOption.getAttribute('data-factor');

        if (factor) {
            factorCostoDisplay.value = factor + 'x';
        }
    }

    // Actualizar cuando cambie la categoría
    categoriaSelect.addEventListener('change', updateFactorCosto);

    // Actualizar en la carga inicial
    updateFactorCosto();
});
</script>
@endsection
