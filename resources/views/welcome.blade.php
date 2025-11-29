<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Academia Armonía - Escuela de Música en La Paz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">

    <!-- Navigation Header -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center space-x-2">
                    <div class="bg-indigo-600 p-2 rounded-lg shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                    </div>
                    <span class="text-lg font-bold text-indigo-600">Academia Armonía</span>
                </a>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#inicio" class="text-gray-600 hover:text-indigo-600 font-medium transition">Inicio</a>
                    <a href="#paquetes" class="text-gray-600 hover:text-indigo-600 font-medium transition">Paquetes</a>
                    <a href="#nosotros" class="text-gray-600 hover:text-indigo-600 font-medium transition">Nosotros</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-indigo-600 hover:text-indigo-700 font-medium transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-600 hover:text-indigo-600 font-medium transition">
                            Ingresar
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                            Registrarse
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="pt-32 pb-20 lg:pt-40 lg:pb-28 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                <h2 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                    Descubre el
                    <span class="text-indigo-600">
                        músico
                    </span>
                    <br/>
                    que llevas dentro
                </h2>

                <p class="text-xl md:text-2xl text-gray-600 mb-10 leading-relaxed max-w-3xl mx-auto">
                    Aprende a tu propio ritmo con nuestro innovador sistema de
                    <strong class="text-gray-800">créditos musicales flexibles</strong>.
                    Clases personalizadas sin fecha de caducidad.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#paquetes" class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 text-white font-bold text-lg rounded-lg shadow-md hover:bg-indigo-700 transition">
                        Ver Paquetes
                    </a>
                    <a href="#nosotros" class="inline-flex items-center justify-center px-8 py-3 bg-white text-gray-800 font-bold text-lg rounded-lg border border-gray-200 shadow-sm hover:bg-gray-100 transition">
                        Conocer Más
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section id="paquetes" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                    Paquetes de Créditos Musicales
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Invierte en tu educación musical con descuentos progresivos.
                    <strong class="text-indigo-600">Cuanto más compras, más ahorras.</strong>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $packages = [
                        ['minutes' => 300, 'hours' => 5, 'discount' => 5, 'name' => 'Básico'],
                        ['minutes' => 600, 'hours' => 10, 'discount' => 10, 'name' => 'Intermedio'],
                        ['minutes' => 900, 'hours' => 15, 'discount' => 15, 'name' => 'Avanzado'],
                        ['minutes' => 1200, 'hours' => 20, 'discount' => 20, 'name' => 'Bronce'],
                        ['minutes' => 1500, 'hours' => 25, 'discount' => 25, 'name' => 'Plata'],
                        ['minutes' => 1800, 'hours' => 30, 'discount' => 30, 'name' => 'Oro'],
                        ['minutes' => 2100, 'hours' => 35, 'discount' => 35, 'name' => 'Platino'],
                        ['minutes' => 2400, 'hours' => 40, 'discount' => 40, 'name' => 'Diamante'],
                        ['minutes' => 2700, 'hours' => 45, 'discount' => 45, 'name' => 'Premium'],
                    ];
                    $basePrice = 25;
                @endphp

                @foreach($packages as $package)
                    @php
                        $originalPrice = $package['minutes'] * $basePrice;
                        $discountedPrice = $originalPrice * (1 - $package['discount'] / 100);
                        $isPremium = $package['name'] === 'Premium';
                    @endphp
                    <div class="relative bg-white rounded-lg shadow-md border {{ $isPremium ? 'border-indigo-500' : 'border-gray-200' }} p-8 flex flex-col">
                        @if($isPremium)
                            <div class="absolute top-0 -translate-y-1/2 left-1/2 -translate-x-1/2">
                                <span class="px-4 py-1 bg-indigo-600 text-white text-sm font-semibold rounded-full shadow-md">
                                    Mejor Valor
                                </span>
                            </div>
                        @endif
                        
                        <h3 class="text-2xl font-bold text-gray-900">{{ $package['name'] }}</h3>
                        <p class="text-gray-600 mt-1">{{ $package['minutes'] }} minutos ({{ $package['hours'] }} horas)</p>
                        
                        <div class="my-6">
                            <div class="flex items-baseline">
                                <span class="text-4xl font-extrabold text-gray-900">{{ number_format($discountedPrice, 0) }}</span>
                                <span class="text-xl text-gray-600 ml-2">Bs</span>
                            </div>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-sm line-through text-gray-400">{{ number_format($originalPrice, 0) }} Bs</span>
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md text-xs font-bold">AHORRA {{ $package['discount'] }}%</span>
                            </div>
                        </div>

                        <ul class="space-y-3 text-gray-600 mb-8">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span>Clases personalizadas</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span>Hasta 4 beneficiarios</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span>Sin fecha de caducidad</span>
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="mt-auto block w-full text-center {{ $isPremium ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-800 hover:bg-gray-900' }} text-white font-bold py-3 rounded-lg transition">
                            Comenzar
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="nosotros" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                        15 años formando
                        <span class="text-indigo-600">
                            músicos talentosos
                        </span>
                    </h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        En <strong class="text-gray-800">Academia Armonía</strong>, creemos que cada estudiante tiene su propio ritmo de aprendizaje.
                        Por eso desarrollamos un innovador <strong>sistema de créditos musicales flexibles</strong> que se adapta a tus necesidades.
                    </p>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Ya sea que quieras concentrar tus clases en vacaciones, prepararte para un examen, o mantener un ritmo constante durante todo el año,
                        nuestro sistema te da la <strong class="text-gray-800">libertad total</strong> para aprender a tu manera.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-indigo-600">Clases Personalizadas</h3>
                        <p class="text-gray-600 mt-2">Atención individual adaptada a tu nivel y objetivos musicales.</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-indigo-600">Flexibilidad Total</h3>
                        <p class="text-gray-600 mt-2">Usa tus créditos cuando quieras, sin presión ni fechas de caducidad.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-20 bg-indigo-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">
                ¿Listo para comenzar tu viaje musical?
            </h2>
            <p class="text-xl text-indigo-100 mb-10 max-w-2xl mx-auto">
                Únete a cientos de estudiantes que ya están aprendiendo a su propio ritmo.
            </p>
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-10 py-4 bg-white text-indigo-700 font-black text-lg rounded-lg shadow-lg hover:bg-gray-100 transition">
                Registrarse Ahora
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} Academia Armonía. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
