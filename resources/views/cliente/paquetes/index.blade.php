@extends('layouts.cliente')

@section('title', 'Paquetes de Créditos')

@section('cliente-content')
{{-- Encabezado de la página --}}
<div class="text-center mb-16">
    <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
        Paquetes de Créditos Musicales
    </h2>
    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
        Invierte en tu educación musical con descuentos progresivos.
        <strong class="text-indigo-600">Cuanto más compras, más ahorras.</strong>
    </p>
</div>

{{-- Grid de Paquetes Predefinidos --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($paquetes as $paquete)
        @php
            $isPremium = $paquete['minutos'] == 2700;
        @endphp
        <div class="relative bg-white rounded-lg shadow-md border {{ $isPremium ? 'border-indigo-500' : 'border-gray-200' }} p-8 flex flex-col">
            @if($isPremium)
                <div class="absolute top-0 -translate-y-1/2 left-1/2 -translate-x-1/2">
                    <span class="px-4 py-1 bg-indigo-600 text-white text-sm font-semibold rounded-full shadow-md">
                        Mejor Valor
                    </span>
                </div>
            @endif

            <h3 class="text-2xl font-bold text-gray-900">
                {{ App\Services\PrecioService::obtenerNombrePaquete($paquete['minutos']) }}
            </h3>
            <p class="text-gray-600 mt-1">{{ $paquete['minutos'] }} minutos ({{ number_format($paquete['horas'], 0) }} horas)</p>

            <div class="my-6">
                <div class="flex items-baseline">
                    <span class="text-4xl font-extrabold text-gray-900">{{ number_format($paquete['total'], 0) }}</span>
                    <span class="text-xl text-gray-600 ml-2">Bs</span>
                </div>
                <div class="flex items-center space-x-2 mt-1">
                    <span class="text-sm line-through text-gray-400">{{ number_format($paquete['subtotal'], 0) }} Bs</span>
                    @if($paquete['porcentaje_descuento'] > 0)
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md text-xs font-bold">AHORRA {{ $paquete['porcentaje_descuento'] }}%</span>
                    @endif
                </div>
            </div>

            <ul class="space-y-3 text-gray-600 mb-8">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>Clases personalizadas</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>Hasta 4 beneficiarios</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>Sin fecha de caducidad</span>
                </li>
            </ul>
            <a href="{{ route('cliente.compras.create', ['minutos' => $paquete['minutos']]) }}"
               class="mt-auto block w-full text-center {{ $isPremium ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-800 hover:bg-gray-900' }} text-white font-bold py-3 rounded-lg transition">
                Comprar Ahora
            </a>
        </div>
    @endforeach
</div>

{{-- Sección de Paquete Personalizado --}}
<div class="mt-16 max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-2 text-center">Paquete Personalizado</h3>
        <p class="text-gray-600 mb-6 text-center">
            ¿Necesitas una cantidad específica? Calcula tu paquete personalizado (mínimo 30 minutos)
        </p>

        <form id="calcularForm" class="space-y-4">
            <div class="flex gap-4">
                <div class="flex-1">
                    <label for="minutos_personalizados" class="block text-sm font-medium text-gray-700 mb-2">
                        Cantidad de Minutos
                    </label>
                    <input
                        type="number"
                        id="minutos_personalizados"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        min="30"
                        step="10"
                        placeholder="Ej: 450">
                    <small class="text-gray-500 text-xs">Mínimo 30 minutos</small>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">
                        Calcular
                    </button>
                </div>
            </div>
        </form>

        {{-- Resultado del cálculo --}}
        <div id="resultado" class="mt-6 hidden">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                <h5 class="text-lg font-bold text-gray-800 mb-4 text-center">Tu Paquete Personalizado</h5>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Minutos:</p>
                        <p class="text-lg font-bold text-gray-900"><span id="res_minutos"></span> min</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Descuento:</p>
                        <p class="text-lg font-bold text-emerald-600"><span id="res_descuento"></span>%</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Subtotal:</p>
                        <p class="text-lg font-bold text-gray-900"><span id="res_subtotal"></span> Bs</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ahorro:</p>
                        <p class="text-lg font-bold text-emerald-600"><span id="res_ahorro"></span> Bs</p>
                    </div>
                </div>
                <div class="text-center mb-4 p-4 bg-white rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Total a Pagar</p>
                    <div class="flex items-baseline justify-center">
                        <span class="text-4xl font-extrabold text-gray-900" id="res_total"></span>
                        <span class="text-xl text-gray-600 ml-2">Bs</span>
                    </div>
                </div>
                <div class="text-center">
                    <a href="#" id="btn_comprar_personalizado" class="inline-block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg transition">
                        Comprar Este Paquete
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Calculadora de precio personalizado
document.getElementById('calcularForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const minutos = document.getElementById('minutos_personalizados').value;

    if (minutos < 30) {
        alert('La cantidad mínima es 30 minutos');
        return;
    }

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
            // Actualizar valores en el resultado
            document.getElementById('res_minutos').textContent = data.minutos;
            document.getElementById('res_subtotal').textContent = data.subtotal.toLocaleString('es-BO', {minimumFractionDigits: 0});
            document.getElementById('res_descuento').textContent = data.porcentaje_descuento;
            document.getElementById('res_ahorro').textContent = data.monto_descuento.toLocaleString('es-BO', {minimumFractionDigits: 0});
            document.getElementById('res_total').textContent = data.total.toLocaleString('es-BO', {minimumFractionDigits: 0});

            // Actualizar enlace de compra
            const btnComprar = document.getElementById('btn_comprar_personalizado');
            btnComprar.href = '{{ route("cliente.compras.create") }}?minutos=' + data.minutos;

            // Mostrar resultado
            document.getElementById('resultado').classList.remove('hidden');
        } else {
            alert('Error al calcular el precio');
        }
    } catch (error) {
        alert('Error de conexión');
        console.error(error);
    }
});
</script>
@endpush
@endsection
