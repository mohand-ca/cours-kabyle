@props(['href', 'active' => false])

<a href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors ' .
            ($active
                ? 'bg-violet-50 text-violet-700'
                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900')
    ]) }}>
    {{ $slot }}
</a>
