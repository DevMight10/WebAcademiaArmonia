@props(['type' => 'button', 'variant' => 'primary'])

@php
$classes = [
    'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    'success' => 'bg-green-600 hover:bg-green-700 text-white',
][$variant];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "px-4 py-2 rounded-lg font-medium transition-colors {$classes}"]) }}>
    {{ $slot }}
</button>
