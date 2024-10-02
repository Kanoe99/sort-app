@props(['printer'])

<x-panel class="flex flex-col text-center">
    <a href="/printers/{{ $printer->id }}">
        <div class="self-start text-sm">{{ $printer->IP }}</div>

        {{-- Check if the printer has a logo and display it --}}
        @if ($printer->logo)
            <img src="{{ asset('storage/' . $printer->logo) }}" alt="Printer Logo"
                class="rounded-xl w-[42px] h-[42px] bg-white">
        @endif

        <div class="py-8">
            <h3 class="group-hover:text-blue-800 text-xl font-bold transition-colors duration-300">
                {{ $printer->model }}

                <p class="mt-6 border-2 py-2">
                    comment = {{ $printer->comment }}
                </p>
            </h3>
        </div>

        <div class="flex justify-between items-center mt-auto">
            <div>
                @if (isset($printer->tags))
                    @foreach ($printer->tags as $tag)
                        <x-tag :$tag size="small" />
                    @endforeach
                @endif
            </div>
        </div>
    </a>
</x-panel>
