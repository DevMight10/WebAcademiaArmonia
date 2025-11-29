@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-violet-100 overflow-hidden']) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-violet-100/80">
            <h3 class="text-lg font-bold text-gray-800">{{ $title }}</h3>
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 bg-gray-50/50 border-t border-violet-100/80">
            {{ $footer }}
        </div>
    @endif
</div>
