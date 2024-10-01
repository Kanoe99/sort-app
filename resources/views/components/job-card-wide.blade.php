@props(['printer'])

<x-panel class="flex gap-x-6">
    <a href="/printers/{{ $printer->id }}">
        <div>
            тут будет лого
        </div>

        <div class="flex-1 flex flex-col">
            <h3 class="font-bold text-xl mt-3 group-hover:text-blue-800">
                <a href="#" target="_blank">
                    {{ $printer->model }}
                </a>
                <p class="mt-6 border-2 py-2">
                    comment =
                    {{ $printer->comment }}
                </p>
            </h3>

        </div>

        <div>
            @if ($printer->tags)
                @foreach ($printer->tags as $tag)
                    <x-tag :$tag />
                @endforeach
            @endif
        </div>
    </a>

</x-panel>
