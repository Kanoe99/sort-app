@php
    $classes = 'mt-6 space-x-3';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</div>
