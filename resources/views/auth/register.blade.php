@extends('layouts.app')

@section('title', 'Registrarse')

@section('content')
<div class="w-full max-w-md mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-indigo-600 px-6 py-4">
            <h2 class="text-2xl font-bold text-white text-center">🎵 Academia Armonía</h2>
            <p class="text-indigo-100 text-center text-sm mt-1">Crea tu cuenta</p>
        </div>

        <!-- Form -->
        <div class="p-6">
            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre Completo <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        pattern="[\p{L}\s\-]+"
                        title="El nombre solo debe contener letras y espacios."
                        autocomplete="name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                        placeholder="Juan Pérez"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 @enderror"
                        placeholder="juan@email.com"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- CI -->
                    <div>
                        <label for="ci" class="block text-sm font-medium text-gray-700 mb-2">
                            CI / Documento <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            inputmode="text"
                            pattern="\d{1,8}(SC|LP|CB|OR|PT|TJ|BE|PD|CH)"
                            title="Ej: 12345678SC. Hasta 8 dígitos y 2 letras de extensión departamental."
                            maxlength="10"
                            id="ci"
                            name="ci"
                            value="{{ old('ci') }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('ci') border-red-300 @enderror"
                            placeholder="12345678SC"
                        >
                        @error('ci')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            title="Este campo solo debe contener números."
                            maxlength="8"
                            minlength="8"
                            id="telefono"
                            name="telefono"
                            value="{{ old('telefono') }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('telefono') border-red-300 @enderror"
                            placeholder="70123456"
                        >
                        @error('telefono')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-300 @enderror"
                        placeholder="Mínimo 8 caracteres"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar Contraseña <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Repite tu contraseña"
                    >
                </div>

                <!-- Tipo de Usuario -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ¿Qué deseas hacer? <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-3">Selecciona al menos una opción</p>

                    <div class="space-y-2">
                        <div class="flex items-start">
                            <input
                                type="checkbox"
                                id="tipo_cliente"
                                name="tipo_usuario[]"
                                value="cliente"
                                {{ in_array('cliente', old('tipo_usuario', [])) ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded mt-0.5"
                            >
                            <label for="tipo_cliente" class="ml-2 block text-sm text-gray-700">
                                <span class="font-medium">Comprar créditos musicales</span>
                                <span class="block text-xs text-gray-500">Podrás adquirir paquetes de créditos para ti o para otros</span>
                            </label>
                        </div>

                        <div class="flex items-start">
                            <input
                                type="checkbox"
                                id="tipo_beneficiario"
                                name="tipo_usuario[]"
                                value="beneficiario"
                                {{ in_array('beneficiario', old('tipo_usuario', [])) ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded mt-0.5"
                            >
                            <label for="tipo_beneficiario" class="ml-2 block text-sm text-gray-700">
                                <span class="font-medium">Tomar clases musicales</span>
                                <span class="block text-xs text-gray-500">Podrás usar créditos para asistir a clases</span>
                            </label>
                        </div>
                    </div>

                    @error('tipo_usuario')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                >
                    Crear Cuenta
                </button>
            </form>

            <!-- Links -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
