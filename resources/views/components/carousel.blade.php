@php
    $classes = 'flex justify-center overflow-hidden gap-[.3rem] relative';
@endphp

<div {{ $attributes(['class' => $classes]) }} id="carousel">
    <!-- Carousel Container (with overflow-hidden) -->
    <div class="flex !w-[93%] overflow-hidden relative" id="carousel-slides">
        <!-- Slot Content (Blocks) -->
        <div class="flex transition-transform gap-2 duration-300 ease-in-out" id="carousel-inner">
            {{ $slot }}
        </div>
    </div>

    <span id="prev-slide" class="angle-left"></span>
    <span id="next-slide" class="angle-right"></span>
</div>
