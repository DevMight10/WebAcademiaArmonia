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
    <p class="text-gray-600">Sigue los pasos para completar tu compra</p>
</div>

{{-- Indicador de progreso --}}
<div class="mb-8">
    <div class="flex items-center justify-between max-w-3xl mx-auto">
        <div class="flex-1 flex items-center">
            <div id="step-indicator-1" class="step-indicator active">
                <div class="step-number">1</div>
                <div class="step-label">Minutos</div>
            </div>
            <div class="step-line"></div>
        </div>
        <div class="flex-1 flex items-center">
            <div id="step-indicator-2" class="step-indicator">
                <div class="step-number">2</div>
                <div class="step-label">Beneficiarios</div>
            </div>
            <div class="step-line"></div>
        </div>
        <div class="flex-1 flex items-center">
            <div id="step-indicator-3" class="step-indicator">
                <div class="step-number">3</div>
                <div class="step-label">Distribución</div>
            </div>
            <div class="step-line"></div>
        </div>
        <div class="flex-1">
            <div id="step-indicator-4" class="step-indicator">
                <div class="step-number">4</div>
                <div class="step-label">Confirmar</div>
            </div>
        </div>
    </div>
</div>

{{-- Formulario de Compra --}}
<x-card class="max-w-4xl mx-auto">
    <form id="form-compra" action="{{ route('cliente.compras.store') }}" method="POST">
        @csrf

        {{-- PASO 1: Cantidad de Minutos --}}
        <div id="paso-1" class="paso-content">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Paso 1: Cantidad de Minutos</h3>

            <div class="mb-6">
                <label for="minutos_totales" class="block text-sm font-medium text-gray-700 mb-2">
                    Minutos a Comprar <span class="text-red-600">*</span>
                </label>
                <input type="number"
                       class="w-full px-4 py-3 text-lg border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       id="minutos_totales"
                       name="minutos_totales"
                       min="30"
                       step="10"
                       value="{{ old('minutos_totales', request()->get('minutos', 300)) }}"
                       required>
                <small class="text-gray-500 text-xs">Mínimo 30 minutos</small>
            </div>

            {{-- Vista previa del precio --}}
            <div id="precio_preview" class="bg-indigo-50 border-2 border-indigo-200 rounded-2xl p-6 mb-6">
                <p class="text-center text-gray-600">Calculando precio...</p>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="irAPaso(2)"
                        class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    Siguiente
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- PASO 2: Seleccionar Beneficiarios --}}
        <div id="paso-2" class="paso-content hidden">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Paso 2: Seleccionar Beneficiarios</h3>

            <x-alert type="info" class="mb-6">
                <p class="text-sm">Puedes asignar créditos a un máximo de 4 personas (incluyéndote). Tú siempre serás el primer beneficiario.</p>
            </x-alert>

            {{-- Cliente (siempre primero) --}}
            <div class="beneficiario-item bg-violet-50 border-2 border-violet-200 rounded-xl p-4 mb-4">
                <div class="flex items-center">
                    <div class="bg-violet-600 text-white rounded-full w-12 h-12 flex items-center justify-center mr-4 font-bold text-lg">
                        1
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-lg">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                        <span class="inline-block mt-1 text-xs bg-violet-100 text-violet-800 px-2 py-1 rounded-full font-semibold">Tú (Cliente)</span>
                    </div>
                </div>
                <input type="hidden" name="beneficiarios[0][user_id]" value="{{ auth()->user()->id }}">
            </div>

            {{-- Beneficiarios adicionales --}}
            <div id="beneficiarios-adicionales" class="space-y-4 mb-6">
                {{-- Se agregan dinámicamente con JavaScript --}}
            </div>

            {{-- Botón para agregar beneficiario --}}
            <button type="button" id="btn-agregar-beneficiario" onclick="mostrarBuscarBeneficiario()"
                    class="w-full border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-violet-500 hover:bg-violet-50 transition-all text-center">
                <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                <p class="text-sm text-gray-600 font-medium">Agregar beneficiario (máximo 3 más)</p>
                <p class="text-xs text-gray-500 mt-1">Introduce el email de un beneficiario registrado</p>
            </button>

            {{-- Modal para buscar beneficiario --}}
            <div id="modal-buscar-beneficiario" class="hidden fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl border-5 border-gray-200">
                    <h4 class="text-lg font-bold mb-4">Buscar Beneficiario</h4>
                    
                    <div class="mb-4">
                        <label for="email-beneficiario" class="block text-sm font-medium text-gray-700 mb-2">
                            Email del beneficiario
                        </label>
                        <input type="email"
                               id="email-beneficiario"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500"
                               placeholder="ejemplo@gmail.com">
                        <p id="error-busqueda" class="text-red-600 text-sm mt-2 hidden"></p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="cerrarModalBuscar()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="button" onclick="buscarBeneficiario()" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors">
                            Buscar
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" onclick="irAPaso(1)" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Anterior
                </button>
                <button type="button" onclick="irAPaso(3)" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    Siguiente
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- PASO 3: Distribuir Minutos --}}
        <div id="paso-3" class="paso-content hidden">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Paso 3: Distribuir Minutos</h3>

            <x-alert type="warning" class="mb-6">
                <p class="text-sm">Cada beneficiario debe recibir al menos 30 minutos. La suma total debe ser igual a los minutos comprados.</p>
            </x-alert>

            <div id="distribuciones-container" class="space-y-6 mb-6">
                {{-- Se genera dinámicamente con JavaScript --}}
            </div>

            {{-- Resumen de distribución --}}
            <div class="bg-gray-100 rounded-xl p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-medium">Minutos asignados:</span>
                    <span id="minutos-asignados" class="font-bold text-lg">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium">Minutos restantes:</span>
                    <span id="minutos-restantes" class="font-bold text-lg text-violet-600">0</span>
                </div>
            </div>

            <div id="error-distribucion" class="hidden bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-red-600 text-sm"></p>
            </div>

            <div class="flex justify-between">
                <button type="button" onclick="irAPaso(2)" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Anterior
                </button>
                <button type="button" onclick="irAPaso(4)" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    Siguiente
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- PASO 4: Confirmar --}}
        <div id="paso-4" class="paso-content hidden">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Paso 4: Confirmar Compra</h3>

            <div id="resumen-compra" class="space-y-6">
                {{-- Se genera dinámicamente con JavaScript --}}
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" onclick="irAPaso(3)" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Anterior
                </button>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700 hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Confirmar Compra
                </button>
            </div>
        </div>
    </form>
</x-card>

@push('styles')
<style>
.paso-content {
    min-height: 400px;
}

.step-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;  
    font-weight: bold;
    margin-bottom: 8px;
    transition: all 0.3s;
}

.step-indicator.active .step-number {
    background: #4f46e5; 
    color: white;
}

.step-indicator.completed .step-number {
    background: #10b981; 
    color: white;
}

.step-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
}

.step-indicator.active .step-label {
    color: #4f46e5;  
    font-weight: 600;
}

.step-line {
    flex: 1;
    height: 2px;
    background: #e5e7eb;
    margin: 0 8px;
    margin-top: -28px;
}
</style>
@endpush

@push('scripts')
<script>
// Estado global
let pasoActual = 1;
let beneficiariosSeleccionados = [
    {
        user_id: {{ auth()->user()->id }},
        nombre: '{{ auth()->user()->name }}',
        email: '{{ auth()->user()->email }}',
        esCliente: true
    }
];

// Navegación entre pasos
function irAPaso(paso) {
    // Validar antes de avanzar
    if (paso > pasoActual) {
        if (!validarPaso(pasoActual)) {
            return;
        }
    }

    // Ocultar paso actual
    document.getElementById(`paso-${pasoActual}`).classList.add('hidden');
    document.getElementById(`step-indicator-${pasoActual}`).classList.remove('active');
    document.getElementById(`step-indicator-${pasoActual}`).classList.add('completed');

    // Mostrar nuevo paso
    document.getElementById(`paso-${paso}`).classList.remove('hidden');
    document.getElementById(`step-indicator-${paso}`).classList.add('active');

    // Acciones específicas por paso
    if (paso === 3) {
        generarDistribuciones();
    } else if (paso === 4) {
        generarResumen();
    }

    pasoActual = paso;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Validar paso antes de avanzar
function validarPaso(paso) {
    if (paso === 1) {
        const minutos = parseInt(document.getElementById('minutos_totales').value);
        if (minutos < 30) {
            alert('Debes comprar al menos 30 minutos');
            return false;
        }
    } else if (paso === 2) {
        if (beneficiariosSeleccionados.length === 0) {
            alert('Debes seleccionar al menos un beneficiario');
            return false;
        }
    } else if (paso === 3) {
        return validarDistribucion();
    }
    return true;
}

// Calcular precio en tiempo real
document.getElementById('minutos_totales').addEventListener('input', async function() {
    const minutos = this.value;
    const precioPreview = document.getElementById('precio_preview');

    if (minutos >= 30) {
        try {
            precioPreview.innerHTML = '<p class="text-center text-gray-600">Calculando precio...</p>';
            
            // ========================================
            // IMPLEMENTACIÓN AJAX CON FETCH API
            // Cálculo de precio en tiempo real (JSON)
            // ========================================
            const response = await fetch('{{ route("cliente.paquetes.calcular") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ minutos: parseInt(minutos) })
            });

            const data = await response.json();
            console.log('Respuesta del servidor:', data);

            if (response.ok) {
                precioPreview.innerHTML = `
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
            } else {
                console.error('Error en la respuesta:', data);
                precioPreview.innerHTML = `
                    <p class="text-center text-red-600">Error al calcular precio. Por favor, intenta de nuevo.</p>
                `;
            }
        } catch (error) {
            console.error('Error calculando precio:', error);
            precioPreview.innerHTML = `
                <p class="text-center text-red-600">Error de conexión. Verifica tu conexión a internet.</p>
            `;
        }
    } else {
        precioPreview.innerHTML = '<p class="text-center text-gray-600">Ingresa al menos 30 minutos</p>';
    }
});

// Modal de búsqueda
function mostrarBuscarBeneficiario() {
    if (beneficiariosSeleccionados.length >= 4) {
        alert('Ya tienes el máximo de 4 beneficiarios');
        return;
    }
    document.getElementById('modal-buscar-beneficiario').classList.remove('hidden');
    document.getElementById('email-beneficiario').value = '';
    document.getElementById('error-busqueda').classList.add('hidden');
}

function cerrarModalBuscar() {
    document.getElementById('modal-buscar-beneficiario').classList.add('hidden');
}

// Buscar beneficiario por email
async function buscarBeneficiario() {
    const email = document.getElementById('email-beneficiario').value;
    const errorDiv = document.getElementById('error-busqueda');

    if (!email) {
        errorDiv.textContent = 'Introduce un email';
        errorDiv.classList.remove('hidden');
        return;
    }

    try {
        // ========================================
        // IMPLEMENTACIÓN AJAX CON FETCH API
        // Búsqueda de beneficiarios por email (JSON)
        // ========================================
        const response = await fetch('{{ route("cliente.beneficiarios.buscar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email })
        });

        const data = await response.json();

        if (data.found) {
            // Verificar si ya está agregado
            if (beneficiariosSeleccionados.some(b => b.user_id === data.beneficiario.user_id)) {
                errorDiv.textContent = 'Este beneficiario ya está agregado';
                errorDiv.classList.remove('hidden');
                return;
            }

            agregarBeneficiario(data.beneficiario);
            cerrarModalBuscar();
        } else {
            errorDiv.textContent = data.message;
            errorDiv.classList.remove('hidden');
        }
    } catch (error) {
        errorDiv.textContent = 'Error al buscar beneficiario';
        errorDiv.classList.remove('hidden');
    }
}

// Agregar beneficiario a la lista
function agregarBeneficiario(beneficiario) {
    beneficiariosSeleccionados.push(beneficiario);
    renderizarBeneficiarios();
}

// Renderizar lista de beneficiarios
function renderizarBeneficiarios() {
    const container = document.getElementById('beneficiarios-adicionales');
    container.innerHTML = '';

    beneficiariosSeleccionados.slice(1).forEach((ben, index) => {
        const realIndex = index + 1;
        const div = document.createElement('div');
        div.className = 'beneficiario-item bg-white border-2 border-gray-200 rounded-xl p-4 flex items-center justify-between';
        div.innerHTML = `
            <div class="flex items-center flex-1">
                <div class="bg-indigo-600 text-white rounded-full w-12 h-12 flex items-center justify-center mr-4 font-bold text-lg">
                    ${realIndex + 1}
                </div>
                <div>
                    <p class="font-semibold text-lg">${ben.nombre}</p>
                    <p class="text-sm text-gray-600">${ben.email}</p>
                </div>
            </div>
            <button type="button" onclick="eliminarBeneficiario(${realIndex})" class="text-red-600 hover:text-red-800 p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <input type="hidden" name="beneficiarios[${realIndex}][user_id]" value="${ben.user_id}">
        `;
        container.appendChild(div);
    });

    // Actualizar botón
    const btn = document.getElementById('btn-agregar-beneficiario');
    if (beneficiariosSeleccionados.length >= 4) {
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.disabled = true;
    } else {
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        btn.disabled = false;
    }
}

// Eliminar beneficiario
function eliminarBeneficiario(index) {
    beneficiariosSeleccionados.splice(index, 1);
    renderizarBeneficiarios();
}

// Generar controles de distribución
function generarDistribuciones() {
    const container = document.getElementById('distribuciones-container');
    const minutosTotales = parseInt(document.getElementById('minutos_totales').value);
    container.innerHTML = '';

    beneficiariosSeleccionados.forEach((ben, index) => {
        const div = document.createElement('div');
        div.className = 'bg-white border border-gray-200 rounded-xl p-4';
        div.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <div class="bg-violet-600 text-white rounded-full w-10 h-10 flex items-center justify-center mr-3 font-bold">
                        ${index + 1}
                    </div>
                    <div>
                        <p class="font-semibold">${ben.nombre}</p>
                        <p class="text-xs text-gray-500">${ben.email}</p>
                    </div>
                </div>
                <span class="text-2xl font-bold text-violet-600" id="display-${index}">0</span>
            </div>
            <input type="range" 
                   min="0" 
                   max="${minutosTotales}" 
                   value="0"
                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                   id="slider-${index}"
                   oninput="actualizarDistribucion(${index}, this.value)">
            <input type="hidden" name="distribuciones[${index}]" id="distribucion-${index}" value="0">
            <div class="flex justify-between text-xs text-gray-500 mt-1">
                <span>0 min</span>
                <span>${minutosTotales} min</span>
            </div>
        `;
        container.appendChild(div);
    });

    document.getElementById('minutos-restantes').textContent = minutosTotales;
}

// Actualizar distribución
function actualizarDistribucion(index, valor) {
    document.getElementById(`display-${index}`).textContent = valor;
    document.getElementById(`distribucion-${index}`).value = valor;

    // Calcular totales
    const minutosTotales = parseInt(document.getElementById('minutos_totales').value);
    let suma = 0;
    beneficiariosSeleccionados.forEach((_, i) => {
        suma += parseInt(document.getElementById(`distribucion-${i}`).value || 0);
    });

    document.getElementById('minutos-asignados').textContent = suma;
    document.getElementById('minutos-restantes').textContent = minutosTotales - suma;
}

// Validar distribución
function validarDistribucion() {
    const minutosTotales = parseInt(document.getElementById('minutos_totales').value);
    let suma = 0;
    const errorDiv = document.getElementById('error-distribucion');

    beneficiariosSeleccionados.forEach((_, index) => {
        const minutos = parseInt(document.getElementById(`distribucion-${index}`).value || 0);
        suma += minutos;

        if (minutos < 30 && minutos > 0) {
            errorDiv.querySelector('p').textContent = 'Cada beneficiario debe recibir al menos 30 minutos';
            errorDiv.classList.remove('hidden');
            return false;
        }
    });

    if (suma !== minutosTotales) {
        errorDiv.querySelector('p').textContent = `La suma (${suma} min) debe ser igual al total (${minutosTotales} min)`;
        errorDiv.classList.remove('hidden');
        return false;
    }

    errorDiv.classList.add('hidden');
    return true;
}

// Generar resumen
function generarResumen() {
    const container = document.getElementById('resumen-compra');
    const minutosTotales = parseInt(document.getElementById('minutos_totales').value);

    let html = `
        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-6 mb-6">
            <h4 class="font-bold text-lg mb-4">Resumen de Compra</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Total de minutos:</span>
                    <span class="font-bold">${minutosTotales} minutos</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h4 class="font-bold text-lg mb-4">Distribución de Créditos</h4>
            <div class="space-y-3">
    `;

    beneficiariosSeleccionados.forEach((ben, index) => {
        const minutos = parseInt(document.getElementById(`distribucion-${index}`).value || 0);
        html += `
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <div class="flex items-center">
                    <div class="bg-violet-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm font-bold">
                        ${index + 1}
                    </div>
                    <div>
                        <p class="font-medium">${ben.nombre}</p>
                        <p class="text-xs text-gray-500">${ben.email}</p>
                    </div>
                </div>
                <span class="font-bold text-violet-600">${minutos} min</span>
            </div>
        `;
    });

    html += `
            </div>
        </div>
    `;

    container.innerHTML = html;
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('minutos_totales').dispatchEvent(new Event('input'));
});
</script>
@endpush
@endsection
