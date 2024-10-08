@props(['printers'])


<section class="mt-6 space-y-6">
    <x-section-heading>Последние добавленные</x-section-heading>
    @if ($printers->isEmpty())
        <x-placeholder>
            Тут нет принтеров <span class="ml-5">(╯°□°）╯︵ ┻━┻</span>
        </x-placeholder>
    @endif

    <div class="slider">
        @foreach ($printers as $printer)
            <x-printer-card-wide :$printer class="item" />
        @endforeach
        <button id="next-list" class="angle-left"></button>
        <button id="prev-list" class="angle-right"></button>
    </div>

</section>

<style>
    .slider {
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        width: 100%;
        height: calc(100vh - 5rem);
        overflow: hidden;
    }

    .item {
        position: absolute;
        width: 80%;
        height: 100%;
        transition: 0.5s;
        left: calc(50% - 110px);
        top: 0;
    }
</style>
