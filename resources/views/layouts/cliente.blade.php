@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex gap-6">
        <!-- Sidebar -->
        <aside class="w-64 bg-white rounded-lg shadow p-4">
            <nav class="space-y-2">
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-indigo-50 text-gray-700 hover:text-indigo-600">
                    Mis Compras
                </a>
                <a href="{{ route('cliente.paquetes.index') }}" class="block px-4 py-2 rounded-lg hover:bg-indigo-50 text-gray-700 hover:text-indigo-600">
                    Paquetes Disponibles
                </a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-indigo-50 text-gray-700 hover:text-indigo-600">
                    Mis Beneficiarios
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1">
            @yield('cliente-content')
        </div>
    </div>
</div>
@endsection
