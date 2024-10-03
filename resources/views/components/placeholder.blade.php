@php
    $classes =
        'text-white flex justify-center text-center font-bold border-2 border-dashed border-white w-full mt-6 px-4 py-6 rounded-xl h-fit';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
