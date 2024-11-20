@props(['printer'])

<x-panel class="flex flex-col text-center w-full lg:w-[48%] xl:w-[49%]">
    <a href="/printers/{{ $printer->id }}" class="space-y-2">
        {{-- Check if the printer has a logo and display it --}}

        {{-- @dd(asset('storage/' . $printer->logo)); --}}
        <div class="flex justify-between">
            @if (empty(json_decode($printer->logo)))
                <div class="rounded-lg w-fit p-1 px-3 border-2 border-dashed text-[9px] h-fit">
                    Место <br />
                    для <br />
                    фото
                </div>
            @else
                @php
                    $logos = json_decode($printer->logo);
                @endphp
                <img src="{{ asset('storage/' . $logos[0]) }}" class="w-[50px] rounded-lg" alt="">
                {{-- @foreach (json_decode($printer->logo) as $logo)
                    <img src="{{ asset('storage/' . $printer->logo) }}" class="w-[42px] rounded-lg" alt="">
                @endforeach --}}
            @endif
            <div class="text-right">
                <div class="text-xs">
                    {{ $printer->number }}
                </div>
                <div class="text-xs">
                    {{ $printer->model }}
                </div>
            </div>
        </div>
        <div class="px-1 py-2 bg-[#090909] rounded-md">
            Локация:
            {{ $printer->location }}
        </div>
        <div class="px-1 py-2 bg-[#090909] rounded-md">
            Статус:
            {{ $printer->status }}
        </div>
    </a>
</x-panel>
