@extends('layouts.cliente')

@section('title', 'Comprar Créditos')

@section('cliente-content')
{{-- Encabezado de la página --}}
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        <svg class="inline-block w-8 h-8 mr-2 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        Nueva Compra de Créditos
    </h1>
    <p class="text-gray-600">Selecciona la cantidad de minutos que deseas comprar</p>
</div>

{{-- Alerta de versión simplificada --}}
<x-alert type="warning" class="mb-6">
    <div class="flex">
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <div>
            <p class="font-semibold">Versión Simplificada</p>
            <p class="text-sm mt-1">Esta es una versión simple del formulario. La versión final multi-paso se implementará próximamente.</p>
        </div>
    </div>
</x-alert>

{{-- Formulario de Compra --}}
<x-card class="max-w-3xl mx-auto">
    <form action="{{ route('cliente.compras.store') }}" method="POST">
        @csrf

        {{-- Paso 1: Cantidad de Minutos --}}
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-600 text-white text-sm font-bold mr-3">
                    1
                </span>
                Cantidad de Minutos
            </h3>

            <div class="mb-4">
                <label for="minutos_totales" class="block text-sm font-medium text-gray-700 mb-2">
                    Minutos a Comprar <span class="text-red-600">*</span>
                </label>
                <input type="number"
                       class="w-full px-4 py-3 text-lg border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('minutos_totales') border-red-500 @enderror"
                       id="minutos_totales"
                       name="minutos_totales"
                       min="30"
                       step="10"
                       value="{{ old('minutos_totales', request('minutos', 300)) }}"
                       required>
                <small class="text-gray-500 text-xs">Mínimo 30 minutos</small>

                @error('minutos_totales')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Vista previa del precio --}}
            <div id="precio_preview" class="bg-indigo-50 border-2 border-indigo-200 rounded-2xl p-6">
                <p class="text-center text-gray-600">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <strong>Precio estimado:</strong> Ingresa la cantidad para ver el total
                </p>
            </div>
        </div>

        {{-- Nota de desarrollo --}}
        <x-alert type="info" class="mb-6">
            <div class="flex">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-sm">Nota para Desarrollo</p>
                    <p class="text-sm mt-1">
                        Por ahora, esta compra se asignará automáticamente al primer cliente y beneficiario en la base de datos.
                        <br><strong>Asegúrate de tener al menos 1 cliente y 1 beneficiario creados.</strong>
                    </p>
                </div>
            </div>
        </x-alert>

        {{-- Campos ocultos temporales (se reemplazarán con el multi-paso real) --}}
        <input type="hidden" name="beneficiarios[0][user_id]" value="1">
        <input type="hidden" id="distribucion_0" name="distribuciones[0]" value="300">

        {{-- Mensajes de error generales --}}
        @if($errors->any() && !$errors->has('minutos_totales'))
            <x-alert type="error" class="mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        {{-- Botones de acción --}}
        <div class="flex flex-col sm:flex-row justify-between gap-4 mt-6">
            <a href="{{ route('cliente.paquetes.index') }}"
               class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Paquetes
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Confirmar Compra
            </button>
        </div>
    </form>
</x-card>

@push('scripts')
<script>
// Calculadora de precio en tiempo real
document.getElementById('minutos_totales').addEventListener('input', async function() {
    const minutos = this.value;
    const distribucion = document.getElementById('distribucion_0');

    // Actualizar distribución con el total
    distribucion.value = minutos;

    if (minutos >= 30) {
        try {
            const response = await fetch('{{ route("cliente.paquetes.calcular") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ minutos: parseInt(minutos) })
            });

            const data = await response.json();

            if (response.ok) {
                document.getElementById('precio_preview').innerHTML = `
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <p class="text-sm text-gray-600">Subtotal:</p>
                            <p class="text-lg font-bold text-gray-900">${data.subtotal.toLocaleString('es-BO', {minimumFractionDigits: 2})} Bs</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Descuento:</p>
                            <p class="text-lg font-bold text-emerald-600">${data.porcentaje_descuento}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ahorro:</p>
                            <p class="text-lg font-bold text-emerald-600">${data.monto_descuento.toLocaleString('es-BO', {minimumFractionDigits: 2})} Bs</p>
                        </div>
                        <div class="col-span-2 text-center p-4 bg-white/80 rounded-xl">
                            <p class="text-sm text-gray-600 mb-1">Total a Pagar</p>
                            <p class="text-3xl font-bold text-indigo-600">
                                ${data.total.toLocaleString('es-BO', {minimumFractionDigits: 2})} Bs
                            </p>
                        </div>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error calculando precio:', error);
        }
    }
});

// Disparar cálculo inicial al cargar la página
document.getElementById('minutos_totales').dispatchEvent(new Event('input'));
</script>
@endpush
@endsection
