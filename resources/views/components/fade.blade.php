<section class="space-y-6">
    <x-section-heading>Требуют Внимания</x-section-heading>

    <div class="relative h-[calc(100vh_-_5rem)]">
        <!-- Scrollable content -->
        <div class="flex flex-wrap gap-2 pb-16 h-full overflow-y-auto justify-between no-scrollbar">

            @if ($aprinters->isEmpty())
                <x-placeholder>
                    Никакой принтер
                    не требует
                    внимания ༼ つ ◕_◕ ༽つ
                </x-placeholder>
            @endif

            @foreach ($aprinters as $printer)
                <x-printer-card :$printer />
            @endforeach
        </div>

        <!-- Apply the gradient and blur effect to the bottom of the container -->
        <div class="fade-bottom fade-bottom-gradient">
            <div class="blur-bottom"></div>
        </div>
    </div>
</section>
