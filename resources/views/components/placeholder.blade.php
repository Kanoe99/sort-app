@php
    $classes = 'text-white text-center font-bold border-2 border-dashed border-white w-full mt-6 px-4 py-6 rounded-xl';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
