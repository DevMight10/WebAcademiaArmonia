<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Academia Armonía')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="antialiased @guest bg-gray-100 @else bg-gray-50 @endguest">
    <div class="min-h-screen flex flex-col">
        @auth
        <!-- Navegación para usuarios autenticados -->
        @include('layouts.navigation')
        @endauth

        <!-- Alertas y Mensajes -->
        @if(session('success') || session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            @if(session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif
            @if(session('error'))
                <x-alert type="error">{{ session('error') }}</x-alert>
            @endif
        </div>
        @endif


        <!-- Page Content -->
        <main class="flex-grow @guest flex items-center justify-center @else py-8 @endguest">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="@guest bg-transparent @else bg-white border-t @endguest mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} Academia Armonía SRL. Todos los derechos reservados.
                </p>
            </div>
        </footer>
    </div>
    
    @stack('scripts')
</body>
</html>
