@props(['printers'])


<section class="mt-6 space-y-6">
    <x-section-heading>Последние добавленные</x-section-heading>
    @if ($printers->isEmpty())
        <x-placeholder>
            Тут нет принтеров <span class="ml-5">(╯°□°）╯︵ ┻━┻</span>
        </x-placeholder>
    @else
        <div class="slider">
            @foreach ($printers as $printer)
                <x-printer-card-wide :$printer class="item" style="transition: 0.5s" />
            @endforeach
            <div class="absolute w-40 flex">
                <div class="hover:bg-white/10 transition duration-300 cursor-pointer py-2 h-full w-16 rounded-md flex justify-center items-center group"
                    id="prev-list">
                    <img src="{{ asset('svg/angle-left.svg') }}" alt="" class="">
                </div>
                <div class="hover:bg-white/10 transition duration-300 py-2 cursor-pointer h-full w-16 rounded-md flex justify-center items-center group"
                    id="next-list">
                    <img src="{{ asset('svg/angle-right.svg') }}" alt="" class="">
                </div>
            </div>
        </div>
    @endif

</section>
