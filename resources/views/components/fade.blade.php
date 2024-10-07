@php
    $classes = 'relative h-[calc(100vh_-_15rem)]'; // Removed the z-index and overflow settings here
@endphp

<section {{ $attributes(['class' => $classes]) }}>
    <!-- Scrollable content -->
    <div class="flex flex-wrap gap-2 pb-16 h-full overflow-y-auto justify-between no-scrollbar">

        {{ $slot }}
    </div>

    <!-- Apply the gradient and blur effect to the bottom of the container -->
    <div class="fade-bottom fade-bottom-gradient">
        <div class="blur-bottom"></div>
    </div>
</section>
