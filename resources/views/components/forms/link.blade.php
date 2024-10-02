@props(['button' => false])

@php
    //hover:animate-dash
    $link =
        'text-sm border-b border-dashed border-white text-white cursor-pointer h-fit w-fit hover:border-blue-500 hover:text-blue-500';
    $buttonClasses =
        'rounded-xl py-4 px-5 font-extrabold border border-2 border-white bg-black text-white cursor-pointer block text-center';
@endphp

@if ($button === true)
    <a {{ $attributes->merge(['class' => $buttonClasses]) }}> {{ $slot }} </a>
@else
    <a {{ $attributes->merge(['class' => $link]) }}> {{ $slot }} </a>
@endif
