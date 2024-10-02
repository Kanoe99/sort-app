@php
    $isChecked = $printer->attention == 1;
    $tags = $printer->tags->pluck('name')->implode(', ');
@endphp

<x-layout>
    <div class="flex gap-5 justify-between items-end max-w-2xl mx-auto">
        <x-page-heading class="w-2/3">Обновить Параметры</x-page-heading>
        <div class="flex w-1/3 gap-2 justify-end">
            <x-forms.link :button="false" href="/printers/{{ $printer->id }}">Назад</x-forms.link>
            <x-forms.link :button="false" href="/">На главную</x-forms.link>
        </div>
    </div>

    <x-forms.form method="POST" action="/printers/{{ $printer->id }}" class="space-y-6 mt-8">
        @method('PATCH')
        <x-forms.input label="Модель" placeholder="Принтер Samsung 400" name="model" type="text"
            value="{{ $printer->model }}" />
        <x-forms.input label="Номер" placeholder="0001" name="number" type="number" min="1" max="16777215"
            value="{{ $printer->number }}" />
        <x-forms.input label="Локация" placeholder="311" name="location" type="text"
            value="{{ $printer->location }}" />
        <x-forms.input label="Статус" placeholder="В эксплуатации" name="status" type="text"
            value="{{ $printer->status }}" />
        <x-forms.input label="Комментарий" placeholder="Вот об этом принтере можно сказать, что.." name="comment"
            type="text" value="{{ $printer->comment }}" />
        <x-forms.input label="IP" placeholder="255.10.192.12" name="IP" type="text"
            value="{{ $printer->IP }}" />
        <x-forms.checkbox label="Особое внимание" name="attention" :checked="$isChecked" />

        <x-forms.divider />

        <x-forms.input label="Теги(через запятую)" name="tags"
            placeholder="хороший, под списание, нужен кому-то в 311" type="text" value="{{ $tags }}" />

        <div class="flex gap-5">
            <x-forms.button type="button" class="hover:border-red-500 !bg-black text-white w-1/3"
                onclick="showModal()">Удалить</x-forms.button>
            <x-forms.button class="w-2/3 hover:border-green-500">Сохранить</x-forms.button>
        </div>
    </x-forms.form>

    <x-forms.form id="delete-form" class="hidden" method="POST" action="/printers/{{ $printer->id }}">
        @csrf
        @method('DELETE')
    </x-forms.form>

    <x-modal />
</x-layout>
