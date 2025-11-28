<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrarse - Academia Armonía</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12">
        <div class="max-w-md w-full">
            <x-card title="Crear Cuenta">
                {{-- Registration form will go here --}}
            </x-card>
        </div>
    </div>
</body>
</html>
