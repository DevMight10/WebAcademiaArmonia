@props(['type' => 'button', 'variant' => 'primary'])

@php
$baseClasses = 'inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all text-sm';

$variantClasses = [
    'primary' => 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white focus:ring-violet-500',
    'secondary' => 'bg-gray-700 hover:bg-gray-800 text-white focus:ring-gray-500',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
    'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500',
    'light' => 'bg-white/80 border border-violet-200 text-violet-700 hover:bg-white hover:border-violet-300 focus:ring-violet-500',
][$variant];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "{$baseClasses} {$variantClasses}"]) }}>
    {{ $slot }}
</button>
