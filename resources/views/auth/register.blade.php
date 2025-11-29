@extends('layouts.app')

@section('title', 'Registrarse')

@section('content')
<div class="w-full max-w-md mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <a href="{{ url('/') }}" class="inline-flex items-center space-x-2">
            <div class="bg-indigo-600 p-2 rounded-lg shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
            </div>
            <span class="text-lg font-bold text-indigo-600">Academia Armonía</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 text-center">Crea tu cuenta</h2>
            <p class="text-gray-600 text-center text-sm mt-1">Únete a nuestra comunidad musical</p>
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
                            id="ci"
                            name="ci"
                            value="{{ old('ci') }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('ci') border-red-300 @enderror"
                            placeholder="1234567"
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
                            type="tel"
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
                    <p class="text-xs text-gray-500 mb-3">Selecciona al menos una opción.</p>

                    <div class="space-y-2">
                        <label for="tipo_cliente" class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-amber-50 hover:border-amber-300 cursor-pointer transition">
                            <input
                                type="checkbox"
                                id="tipo_cliente"
                                name="tipo_usuario[]"
                                value="cliente"
                                {{ in_array('cliente', old('tipo_usuario', [])) ? 'checked' : '' }}
                                class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded mt-0.5"
                            >
                            <div class="ml-3 block text-sm text-gray-700">
                                <span class="font-medium">Comprar créditos musicales</span>
                                <span class="block text-xs text-gray-500">Para adquirir paquetes de créditos para ti u otros.</span>
                            </div>
                        </label>

                        <label for="tipo_beneficiario" class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-sky-50 hover:border-sky-300 cursor-pointer transition">
                            <input
                                type="checkbox"
                                id="tipo_beneficiario"
                                name="tipo_usuario[]"
                                value="beneficiario"
                                {{ in_array('beneficiario', old('tipo_usuario', [])) ? 'checked' : '' }}
                                class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-gray-300 rounded mt-0.5"
                            >
                            <div class="ml-3 block text-sm text-gray-700">
                                <span class="font-medium">Tomar clases musicales</span>
                                <span class="block text-xs text-gray-500">Para usar créditos y asistir a clases.</span>
                            </div>
                        </label>
                    </div>

                    @error('tipo_usuario')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-emerald-600 text-white py-2.5 px-4 rounded-md font-semibold hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all"
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
