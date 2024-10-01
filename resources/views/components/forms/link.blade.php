@php
    //hover:animate-dash
    $classes = 'text-sm border-b border-dashed border-white text-white cursor-pointer';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}> {{ $slot }} </a>
