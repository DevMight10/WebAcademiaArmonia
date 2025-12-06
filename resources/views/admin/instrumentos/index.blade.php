@extends('layouts.admin')

@section('title', 'Gestión de Instrumentos')

@section('admin-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Instrumentos Musicales</h1>
            <p class="mt-1 text-sm text-gray-600">Gestiona el catálogo de instrumentos de la academia</p>
        </div>
        <a href="{{ route('admin.instrumentos.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Instrumento
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Filters Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <form id="form-filtros" action="{{ route('admin.instrumentos.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Búsqueda -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text"
                           name="search"
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Nombre del instrumento..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <!-- Filtro por Categoría -->
                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select name="categoria"
                            id="categoria"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Todas</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->value }}" {{ request('categoria') == $cat->value ? 'selected' : '' }}>
                                {{ $cat->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Estado -->
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado"
                            id="estado"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                    Filtrar
                </button>
                <a href="{{ route('admin.instrumentos.index') }}"
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($instrumentos->count() > 0)
            <div class="overflow-x-auto">
                <table id="tabla-instrumentos" class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoría
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Factor de Costo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($instrumentos as $instrumento)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"></path>
                                        </svg>
                                        <div class="text-sm font-medium text-gray-900">{{ $instrumento->nombre }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $categoriaEnum = App\Enums\CategoriaInstrumento::from($instrumento->categoria);
                                        $badgeColors = [
                                            'basico' => 'bg-green-100 text-green-800',
                                            'intermedio' => 'bg-blue-100 text-blue-800',
                                            'avanzado' => 'bg-yellow-100 text-yellow-800',
                                            'especializado' => 'bg-purple-100 text-purple-800',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeColors[$instrumento->categoria] }}">
                                        {{ $categoriaEnum->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $instrumento->factor_costo }}x</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($instrumento->estado)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Activo
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.instrumentos.show', $instrumento) }}"
                                           class="text-indigo-600 hover:text-indigo-900"
                                           title="Ver">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.instrumentos.edit', $instrumento) }}"
                                           class="text-yellow-600 hover:text-yellow-900"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        @if($instrumento->estado)
                                            <form action="{{ route('admin.instrumentos.destroy', $instrumento) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('¿Desactivar este instrumento?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900"
                                                        title="Desactivar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.instrumentos.restore', $instrumento) }}"
                                                  method="POST"
                                                  class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="text-green-600 hover:text-green-900"
                                                        title="Activar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <span id="paginacion-info" class="text-sm text-gray-600"></span>
                {{ $instrumentos->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay instrumentos</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo instrumento.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.instrumentos.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nuevo Instrumento
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- jQuery CDN y JavaScript para AJAX --}}
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // Variables para debounce
    let searchTimeout = null;
    const DEBOUNCE_DELAY = 300; // ms

    // Función principal para buscar con AJAX
    function buscarInstrumentos() {
        const search = $('#search').val();
        const categoria = $('#categoria').val();
        const estado = $('#estado').val();

        // Mostrar indicador de carga
        $('#tabla-instrumentos tbody').html(`
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <svg class="animate-spin h-8 w-8 mx-auto text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-2 text-gray-500">Buscando...</p>
                </td>
            </tr>
        `);

        // Petición AJAX con jQuery
        $.ajax({
            url: '{{ route("admin.instrumentos.index") }}',
            method: 'GET',
            data: { search, categoria, estado },
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                renderizarTabla(response.instrumentos);
                renderizarPaginacion(response.pagination);
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', error);
                $('#tabla-instrumentos tbody').html(`
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-red-600">
                            Error al cargar los datos. Intenta de nuevo.
                        </td>
                    </tr>
                `);
            }
        });
    }

    // Renderizar tabla con los datos JSON
    function renderizarTabla(instrumentos) {
        if (instrumentos.length === 0) {
            $('#tabla-instrumentos tbody').html(`
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        No se encontraron instrumentos con esos filtros.
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        instrumentos.forEach(function(inst) {
            const badgeColors = {
                'basico': 'bg-green-100 text-green-800',
                'intermedio': 'bg-blue-100 text-blue-800',
                'avanzado': 'bg-yellow-100 text-yellow-800',
                'especializado': 'bg-purple-100 text-purple-800'
            };
            const categoriaLabels = {
                'basico': 'Básico',
                'intermedio': 'Intermedio',
                'avanzado': 'Avanzado',
                'especializado': 'Especializado'
            };

            html += `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-indigo-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"></path>
                            </svg>
                            <div class="text-sm font-medium text-gray-900">${inst.nombre}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${badgeColors[inst.categoria] || 'bg-gray-100 text-gray-800'}">
                            ${categoriaLabels[inst.categoria] || inst.categoria}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${inst.factor_costo}x</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${inst.estado ? 
                            '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>' :
                            '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>'
                        }
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="/admin/instrumentos/${inst.id}" class="text-indigo-600 hover:text-indigo-900" title="Ver">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="/admin/instrumentos/${inst.id}/edit" class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        });

        $('#tabla-instrumentos tbody').html(html);
    }

    // Renderizar información de paginación
    function renderizarPaginacion(pagination) {
        $('#paginacion-info').html(`
            Mostrando <strong>${pagination.total}</strong> instrumentos
            (Página ${pagination.current_page} de ${pagination.last_page})
        `);
    }

    // Event listeners con debounce para búsqueda en tiempo real
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(buscarInstrumentos, DEBOUNCE_DELAY);
    });

    // Filtros de categoría y estado sin debounce
    $('#categoria, #estado').on('change', function() {
        buscarInstrumentos();
    });

    // Prevenir submit del formulario (ya no necesitamos recargar)
    $('#form-filtros').on('submit', function(e) {
        e.preventDefault();
        buscarInstrumentos();
    });
});
</script>
@endpush
@endsection
