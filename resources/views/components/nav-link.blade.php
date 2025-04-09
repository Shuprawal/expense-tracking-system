@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'inline-flex items-center px-3 py-2 border-b-2 border-indigo-500 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent transition duration-300 ease-in-out'
        : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-sm font-medium text-white hover:text-indigo-400 hover:border-indigo-400 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
