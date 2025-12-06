@extends('layouts.beneficiario')

@section('title', 'Agendar Clase')

@section('beneficiario-content')
{{-- Encabezado --}}
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        <svg class="inline-block w-8 h-8 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Agendar Clase
    </h1>
    <p class="text-gray-600">Agenda tu próxima clase de música</p>
</div>

{{-- Créditos Disponibles --}}
<x-card class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600 mb-1">Créditos Disponibles</p>
            <p class="text-4xl font-bold text-indigo-600">{{ $totalMinutosDisponibles }}</p>
            <p class="text-sm text-gray-500">minutos</p>
        </div>
        <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
</x-card>

{{-- Mensajes --}}
@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
@endif

{{-- Formulario de Agendamiento --}}
<x-card>
    <form method="POST" action="{{ route('beneficiario.agendamiento.store') }}" id="form-agendamiento">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Instrumento --}}
            <div>
                <label for="instrumento_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Instrumento <span class="text-red-500">*</span>
                </label>
                <select id="instrumento_id" 
                        name="instrumento_id" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Selecciona un instrumento</option>
                    @foreach($instrumentos as $instrumento)
                        <option value="{{ $instrumento->id }}">{{ $instrumento->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Instructor --}}
            <div>
                <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Instructor <span class="text-red-500">*</span>
                </label>
                <select id="instructor_id" 
                        name="instructor_id" 
                        required
                        disabled
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 disabled:bg-gray-100">
                    <option value="">Primero selecciona un instrumento</option>
                </select>
            </div>

            {{-- Fecha --}}
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       id="fecha" 
                       name="fecha"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- Duración --}}
            <div>
                <label for="duracion_minutos" class="block text-sm font-medium text-gray-700 mb-2">
                    Duración <span class="text-red-500">*</span>
                </label>
                <select id="duracion_minutos" 
                        name="duracion_minutos" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Selecciona duración</option>
                    <option value="30">30 minutos</option>
                    <option value="45">45 minutos</option>
                    <option value="60">60 minutos (1 hora)</option>
                    <option value="90">90 minutos (1.5 horas)</option>
                    <option value="120">120 minutos (2 horas)</option>
                </select>
            </div>
        </div>

        {{-- Horarios Disponibles --}}
        <div class="mb-6" id="horarios-container" style="display: none;">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Horario Disponible <span class="text-red-500">*</span>
            </label>
            <div id="horarios-grid" class="grid grid-cols-4 md:grid-cols-6 gap-2">
                <!-- Se llenarán con JavaScript -->
            </div>
            <input type="hidden" name="hora" id="hora-selected">
        </div>

        {{-- Observaciones --}}
        <div class="mb-6">
            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                Observaciones (opcional)
            </label>
            <textarea id="observaciones" 
                      name="observaciones" 
                      rows="3"
                      maxlength="500"
                      placeholder="Alguna nota especial para tu clase..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
        </div>

        {{-- Resumen --}}
        <div id="resumen-cita" class="mb-6 p-4 bg-gray-50 rounded-lg" style="display: none;">
            <h3 class="font-bold text-gray-900 mb-2">Resumen de tu cita:</h3>
            <div class="space-y-1 text-sm text-gray-700">
                <p><strong>Instrumento:</strong> <span id="resumen-instrumento">-</span></p>
                <p><strong>Instructor:</strong> <span id="resumen-instructor">-</span></p>
                <p><strong>Fecha:</strong> <span id="resumen-fecha">-</span></p>
                <p><strong>Hora:</strong> <span id="resumen-hora">-</span></p>
                <p><strong>Duración:</strong> <span id="resumen-duracion">-</span></p>
                <p class="text-indigo-600 font-bold"><strong>Créditos a consumir:</strong> <span id="resumen-creditos">-</span> minutos</p>
            </div>
        </div>

        {{-- Botones --}}
        <div class="flex gap-3">
            <button type="submit" 
                    id="btn-agendar"
                    disabled
                    class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed">
                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Agendar Clase
            </button>
            <a href="{{ route('beneficiario.citas.index') }}" 
               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition-colors">
                Ver Mis Citas
            </a>
        </div>
    </form>
</x-card>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const instrumentoSelect = document.getElementById('instrumento_id');
    const instructorSelect = document.getElementById('instructor_id');
    const fechaInput = document.getElementById('fecha');
    const duracionSelect = document.getElementById('duracion_minutos');
    const horariosContainer = document.getElementById('horarios-container');
    const horariosGrid = document.getElementById('horarios-grid');
    const horaInput = document.getElementById('hora-selected');
    const btnAgendar = document.getElementById('btn-agendar');
    const resumenDiv = document.getElementById('resumen-cita');

    let instructoresData = [];

    // Cuando cambia el instrumento, cargar instructores
    instrumentoSelect.addEventListener('change', async function() {
        const instrumentoId = this.value;
        instructorSelect.innerHTML = '<option value="">Cargando...</option>';
        instructorSelect.disabled = true;
        horariosContainer.style.display = 'none';

        if (!instrumentoId) {
            instructorSelect.innerHTML = '<option value="">Primero selecciona un instrumento</option>';
            return;
        }

        try {
            const response = await fetch(`{{ route('beneficiario.agendamiento.instructores') }}?instrumento_id=${instrumentoId}`);
            instructoresData = await response.json();

            instructorSelect.innerHTML = '<option value="">Selecciona un instructor</option>';
            instructoresData.forEach(instructor => {
                const option = document.createElement('option');
                option.value = instructor.id;
                option.textContent = instructor.user.name;
                instructorSelect.appendChild(option);
            });
            instructorSelect.disabled = false;
        } catch (error) {
            console.error('Error al cargar instructores:', error);
            instructorSelect.innerHTML = '<option value="">Error al cargar instructores</option>';
        }
    });

    // Cuando cambian instructor, fecha o duración, cargar horarios
    [instructorSelect, fechaInput, duracionSelect].forEach(element => {
        element.addEventListener('change', cargarHorarios);
    });

    async function cargarHorarios() {
        const instructorId = instructorSelect.value;
        const fecha = fechaInput.value;
        const duracion = duracionSelect.value;

        if (!instructorId || !fecha || !duracion) {
            horariosContainer.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`{{ route('beneficiario.agendamiento.disponibilidad') }}?instructor_id=${instructorId}&fecha=${fecha}&duracion=${duracion}`);
            const horarios = await response.json();

            horariosGrid.innerHTML = '';
            horarios.forEach(horario => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'px-3 py-2 border rounded-lg text-sm font-medium transition-colors hover:bg-indigo-50 hover:border-indigo-500';
                btn.textContent = horario.hora;
                btn.onclick = () => seleccionarHorario(horario.hora, btn);
                horariosGrid.appendChild(btn);
            });

            horariosContainer.style.display = horarios.length > 0 ? 'block' : 'none';
        } catch (error) {
            console.error('Error al cargar horarios:', error);
        }
    }

    function seleccionarHorario(hora, btn) {
        // Remover selección anterior
        document.querySelectorAll('#horarios-grid button').forEach(b => {
            b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
        });

        // Marcar como seleccionado
        btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
        horaInput.value = hora;

        // Actualizar resumen
        actualizarResumen();
    }

    function actualizarResumen() {
        const instrumento = instrumentoSelect.options[instrumentoSelect.selectedIndex]?.text;
        const instructor = instructorSelect.options[instructorSelect.selectedIndex]?.text;
        const fecha = fechaInput.value;
        const hora = horaInput.value;
        const duracion = duracionSelect.value;

        if (instrumento && instructor && fecha && hora && duracion) {
            document.getElementById('resumen-instrumento').textContent = instrumento;
            document.getElementById('resumen-instructor').textContent = instructor;
            document.getElementById('resumen-fecha').textContent = new Date(fecha).toLocaleDateString('es-ES');
            document.getElementById('resumen-hora').textContent = hora;
            document.getElementById('resumen-duracion').textContent = duracion + ' minutos';
            document.getElementById('resumen-creditos').textContent = duracion;
            
            resumenDiv.style.display = 'block';
            btnAgendar.disabled = false;
        } else {
            resumenDiv.style.display = 'none';
            btnAgendar.disabled = true;
        }
    }
});
</script>
@endpush
@endsection
