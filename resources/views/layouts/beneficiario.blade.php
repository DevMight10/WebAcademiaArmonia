@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex gap-6">
        <!-- Sidebar de Navegación del Beneficiario -->
        <aside class="w-64 bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-indigo-100 p-4 sticky top-4 h-screen">
            <nav class="space-y-2">
                {{-- Dashboard --}}
                <a href="{{ route('beneficiario.dashboard') }}"
                   class="block px-4 py-2.5 rounded-xl {{ request()->routeIs('beneficiario.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }} transition-all font-medium">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>

                {{-- Mis Créditos --}}
                <a href="{{ route('beneficiario.creditos.index') }}"
                   class="block px-4 py-2.5 rounded-xl {{ request()->routeIs('beneficiario.creditos.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }} transition-all font-medium">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Mis Créditos
                </a>

                {{-- Agendar Clase --}}
                <a href="{{ route('beneficiario.agendamiento.index') }}"
                   class="block px-4 py-2.5 rounded-xl {{ request()->routeIs('beneficiario.agendamiento.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }} transition-all font-medium">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Agendar Clase
                </a>

                {{-- Mis Citas --}}
                <a href="{{ route('beneficiario.citas.index') }}"
                   class="block px-4 py-2.5 rounded-xl {{ request()->routeIs('beneficiario.citas.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }} transition-all font-medium">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Mis Citas
                </a>

                {{-- Historial --}}
                <a href="{{ route('beneficiario.historial.index') }}"
                   class="block px-4 py-2.5 rounded-xl {{ request()->routeIs('beneficiario.historial.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }} transition-all font-medium">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Historial
                </a>
            </nav>
        </aside>

        <!-- Contenido Principal -->
        <div class="flex-1 min-h-screen">
            @yield('beneficiario-content')
        </div>
    </div>
</div>
@endsection
