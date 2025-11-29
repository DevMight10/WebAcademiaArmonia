@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex gap-6">
        <!-- Sidebar de Navegación del Cliente -->
        <aside class="w-64 bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-indigo-100 p-4 sticky top-4 h-screen">
            <nav class="space-y-2">
                {{-- Dashboard --}}
                <a href="{{ route('cliente.dashboard') }}"
                   class="block px-4 py-2.5 rounded-xl {{ request()->routeIs('cliente.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }} transition-all font-medium">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>

                {{-- Paquetes Disponibles --}}
                <a href="{{ route('cliente.paquetes.index') }}"
                   class="block px-4 py-2.5 rounded-xl {{ request()->routeIs('cliente.paquetes.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }} transition-all font-medium">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Paquetes
                </a>

                {{-- Nueva Compra --}}
                <a href="{{ route('cliente.compras.create') }}"
                   class="block px-4 py-2.5 rounded-xl {{ request()->routeIs('cliente.compras.create') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }} transition-all font-medium">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nueva Compra
                </a>

                {{-- Mis Compras --}}
                <a href="{{ route('cliente.compras.index') }}"
                   class="block px-4 py-2.5 rounded-xl {{ request()->routeIs('cliente.compras.index') || request()->routeIs('cliente.compras.confirmacion') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }} transition-all font-medium">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Mis Compras
                </a>
            </nav>
        </aside>

        <!-- Contenido Principal -->
        <div class="flex-1 min-h-screen">
            @yield('cliente-content')
        </div>
    </div>
</div>
@endsection