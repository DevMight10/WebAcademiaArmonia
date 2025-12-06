@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex gap-6">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white rounded-lg shadow p-4 flex-shrink-0">
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-50 text-gray-700 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.instrumentos.index') }}"
                   class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.instrumentos.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-50 text-gray-700 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                    Instrumentos
                </a>
                <a href="{{ route('admin.instructores.index') }}"
                   class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.instructores.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-50 text-gray-700 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Instructores
                </a>
                <a href="{{ route('admin.clientes.index') }}"
                   class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.clientes.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-50 text-gray-700 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Clientes
                </a>
                <a href="{{ route('admin.beneficiarios.index') }}"
                   class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.beneficiarios.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-50 text-gray-700 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Beneficiarios
                </a>
                <a href="{{ route('admin.reportes.index') }}"
                   class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.reportes.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-50 text-gray-700 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Reportes
                </a>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 min-w-0 max-w-5xl">
            @yield('admin-content')
        </div>
    </div>
</div>
@endsection
