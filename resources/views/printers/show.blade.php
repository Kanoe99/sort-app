<x-layout>
    <x-panel class="flex flex-col text-center">
        <div class="self-start text-sm">{{ $printer->model }}</div>

        <div class="py-8">
            <h3 class="group-hover:text-blue-800 text-xl font-bold transition-colors duration-300">
                {{ $printer->model }}

                <p class="mt-6 border-2 py-2">
                    comment =
                    {{ $printer->comment }}
                </p>
            </h3>
        </div>

        <div class="flex justify-between items-center mt-auto">
            <div>
                @if (isset($printer->tags))
                    @foreach ($printer->tags as $tag)
                        <x-tag :$tag />
                    @endforeach
                @endif
            </div>
            <x-forms.button>Редактировать</x-forms.button>
        </div>
    </x-panel>
</x-layout>
