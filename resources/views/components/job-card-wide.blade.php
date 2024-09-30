@props(['printer'])

<x-panel class="flex gap-x-6">
    <div>
        тут будет лого
    </div>

    <div class="flex-1 flex flex-col">
        {{-- <a href="#"
            class="self-start text-sm text-gray-400 transition-colors duration-300">{{ $job->employer->name }}</a> --}}

        <h3 class="font-bold text-xl mt-3 group-hover:text-blue-800">
            <a href="#" target="_blank">
                {{ $printer->model }}
            </a>
        </h3>

    </div>

    <div>
        @foreach ($printer->tags as $tag)
            <x-tag :$tag />
        @endforeach
    </div>
</x-panel>
