<x-layout>
    <x-page-heading>Новый Принтер</x-page-heading>

    <x-forms.form method="POST" action="/printers" class="space-y-6" enctype="multipart/form-data">
        <x-forms.input label="Модель" placeholder="Принтер Samsung 400" name="model" type="text" />
        <x-forms.input label="Номер" placeholder="0001" name="number" type="number" min="1" max="16777215" />
        <x-forms.input label="Локация" placeholder="311" name="location" type="text" />
        <x-forms.input label="Статус" placeholder="В эксплуатации" name="status" type="text" />
        <x-forms.input label="Комментарий" placeholder="Вот об этом принтере можно сказать, что.." name="comment"
            type="text" />
        <x-forms.input label="IP" placeholder="255.10.192.12" name="IP" type="text" />
        <x-forms.checkbox label="Особое внимание" name="attention" />

        <div class="mb-4">
            <x-forms.input label="Загрузить фото (.jpg, .jpeg, .png)" type="file" name="logo[]" id="logowide"
                accept=".jpg, .jpeg, .png" class="mt-1 block w-full" multiple />
        </div>

        <x-forms.divider />

        <x-forms.input label="Теги(через запятую)" name="tags"
            placeholder="хороший, под списание, нужен кому-то в 311" type="text" />

        <x-forms.button class="w-full">Добавить</x-forms.button>
    </x-forms.form>
</x-layout>
