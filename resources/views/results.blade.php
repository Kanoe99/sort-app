<x-layout>
    <x-page-heading class="!mt-6">Результаты Поиска</x-page-heading>

    <div class="flex flex-col space-y-10 mt-6">
        @if ($printers->isNotEmpty())
            @foreach ($printers as $printer)
                <x-printer-card-wide :$printer />
            @endforeach
        @else
            <x-placeholder>
                По запросу
                <span class="mx-4 border-b">{{ request()->query('q') }}</span>
                ничего не нашлось
            </x-placeholder>
        @endif
    </div>
</x-layout>
