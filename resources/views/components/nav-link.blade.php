@props(['href', 'active' => false])

<a href="{{ $href }}"
    class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $active ? 'text-violet-700 bg-violet-50' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
    {{ $slot }}
</a>
