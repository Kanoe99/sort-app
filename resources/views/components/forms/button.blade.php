@props(['a' => false, 'cancel' => false])
@php
    $classes =
        'bg-white rounded-xl py-4 px-5 font-extrabold border border-2 border-white text-black hover:bg-black hover:text-white transition duration-300';
@endphp


@if ($a === true)
    <button {{ $attributes(['class' => $classes]) }}>
        <a {{ $attributes }}>{{ $slot }}</a>
    </button>
@else
    <button {{ $attributes(['class' => $classes]) }}>{{ $slot }}</button>
@endif
