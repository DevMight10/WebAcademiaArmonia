@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="w-full max-w-md mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-indigo-600 px-6 py-4">
            <h2 class="text-2xl font-bold text-white text-center">🎵 Academia Armonía</h2>
            <p class="text-indigo-100 text-center text-sm mt-1">Inicia sesión en tu cuenta</p>
        </div>

        <!-- Form -->
        <div class="p-6">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 @enderror"
                        placeholder="tu@email.com"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-300 @enderror"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-6">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Recuérdame
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                >
                    Iniciar Sesión
                </button>
            </form>

            <!-- Links -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                        Regístrate aquí
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Credenciales de prueba -->
    <div class="mt-6 bg-gray-100 border border-gray-300 rounded-lg p-4">
        <p class="text-xs text-gray-600 font-semibold mb-2">Credenciales de prueba:</p>
        <div class="space-y-1 text-xs text-gray-600">
            <p><strong>Admin:</strong> admin@armonia.com / admin123</p>
            <p><strong>Coordinador:</strong> coordinador@armonia.com / coord123</p>
        </div>
    </div>
</div>
@endsection
