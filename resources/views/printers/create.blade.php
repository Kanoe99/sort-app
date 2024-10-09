<x-layout>
    <x-page-heading>Новый Принтер</x-page-heading>

    <x-forms.form method="POST" action="/printers" class="space-y-6" enctype="multipart/form-data">
        <x-forms.input label="Тип оборудования" placeholder="Принтер" name="type" type="text" />
        <x-forms.input label="Модель" placeholder="Принтер Samsung 400" name="model" type="text" />
        <x-forms.input label="Инвентарный номер" placeholder="0001" name="number" type="number" min="1"
            max="16777215" />
        <x-forms.input label="Локация" placeholder="311" name="location" type="text" />
        <x-forms.input label="Статус" placeholder="В эксплуатации" name="status" type="text" />
        <x-forms.input label="Комментарий" placeholder="Вот об этом принтере можно сказать, что.." name="comment"
            type="text" />
        <x-forms.select name="ip_exists" label="Есть IP?" id="ip-select" class="w-full">
            <option value="yes">Есть</option>
            <option value="no">Нету</option>
        </x-forms.select>
        <div>
            <div class="inline-flex items-center gap-x-2" id="ip-square">
                <span class="w-2 h-2 bg-white inline-block"></span>
                <label class="font-bold" for="ip" id="ip-label">IP адрес</label>
            </div>
            <x-forms.input label="" placeholder="255.10.192.12" name="IP" id="ip" />
        </div>

        <div class="flex justify-between items-center gap-5">
            <x-forms.input label="Счётчик страниц" placeholder="1000" name="counter" type="text" />
        </div>


        <x-forms.input label="Дата последнего ремонта" name="fixDate" type="date"
            value="{{ \Carbon\Carbon::now()->format('m-d-Y') }}" placeholder="дд.мм.гггг" />


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
