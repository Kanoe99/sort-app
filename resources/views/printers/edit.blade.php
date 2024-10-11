@php
    $isChecked = $printer->attention == 1;
    $tags = $printer->tags->pluck('name')->implode(', ');
    $upload = ($printer->logo ? 'Обновить' : 'Загрузить') . ' фото';
@endphp

<x-layout>
    <div class="flex gap-5 justify-between items-end max-w-2xl mx-auto mt-6">
        <x-page-heading class="w-2/3 !text-left">Обновить Параметры</x-page-heading>
        <div class="flex w-1/3 gap-2 justify-end">
            <x-forms.link :button="false" href="/printers/{{ $printer->id }}">Назад</x-forms.link>
            <x-forms.link :button="false" href="/">На главную</x-forms.link>
        </div>
    </div>

    <x-forms.form method="POST" action="/printers/{{ $printer->id }}" class="space-y-6 mt-8"
        enctype="multipart/form-data">
        @method('PATCH')
        @csrf

        <x-forms.input label="Тип оборудования" placeholder="Принтер" name="type" type="text"
            value="{{ $printer->type }}" />
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


        <x-ip-address :printer="$printer" />

        <script>
            function toggleIpField() {
                const select = document.getElementById('ip-select');
                const ipContainer = document.getElementById('ip-container');

                if (select.value === 'yes') {
                    ipContainer.style.display = 'block';
                } else {
                    ipContainer.style.display = 'none';
                }
            }

            // Initialize visibility based on the current select value on page load
            document.addEventListener('DOMContentLoaded', () => {
                toggleIpField();
            });
        </script>


        <div class="flex justify-between items-center gap-5">
            <x-forms.input label="Счётчик страниц" placeholder="1000" name="counter" type="text"
                value="{{ $printer->counter }}" />
            <x-panel class="mt-[1.7rem] text-nowrap">
                Дата последнего изменения -
                {{ $printer->counterDate }}
            </x-panel>
        </div>


        <x-forms.input label="Дата последнего ремонта" placeholder="01.10.2024" name="fixDate" type="date"
            value="{{ \Carbon\Carbon::parse($printer->fixDate)->format('Y-m-d') }}" />


        <x-forms.checkbox label="Особое внимание" name="attention" checked="{{ $isChecked }}" />

        <!-- Plus sign to add photos -->
        <div class="flex gap-2">
            <label for="logo-upload" class="cursor-pointer text-blue-500 text-sm">+ Добавить фото</label>
            <input type="file" id="logo-upload" name="logo[]" accept=".jpg,.jpeg,.png" multiple class="hidden"
                onchange="handleFileSelect(this)">
        </div>

        <!-- Preview existing and new photos -->
        <div id="logo-preview" class="flex gap-2 mt-4 flex-wrap">
            @php
                $logos = json_decode($printer->logo);
            @endphp

            <!-- Display existing photos with 'X' for removal -->
            @if ($logos)
                @foreach ($logos as $index => $logo)
                    <div class="relative" id="existing-logo-{{ $index }}">
                        <img src="{{ asset('storage/' . $logo) }}" class="w-1/4">
                        <button type="button" class="absolute top-0 right-0 text-red-500"
                            onclick="removeExistingPhoto('{{ $logo }}', {{ $index }})">
                            X
                        </button>
                    </div>
                @endforeach
            @else
                <x-placeholder>Тут пока ничего нет</x-placeholder>
            @endif
        </div>

        <!-- Hidden input to track removed photos -->
        <input type="hidden" name="removed_logos" id="removed-logos">

        <x-forms.divider />

        <x-forms.input label="Теги (через запятую)" name="tags"
            placeholder="хороший, под списание, нужен кому-то в 311" type="text" value="{{ $tags }}" />

        <div class="flex gap-5">
            <x-forms.button type="button" id="showModal" class="hover:border-red-500 !bg-black text-white w-1/3">
                Удалить
            </x-forms.button>
            <x-forms.button class="w-2/3 hover:border-green-500">Сохранить</x-forms.button>
        </div>
    </x-forms.form>

    <x-forms.form id="delete-form" class="hidden" method="POST" action="/printers/{{ $printer->id }}">
        @csrf
        @method('DELETE')
    </x-forms.form>

    <x-modal />
</x-layout>

<script>
    let removedLogos = []; // Array to track removed logos

    // Handle the file selection and display previews
    function handleFileSelect(input) {
        const fileInput = document.getElementById('logo-upload');
        const logoPreview = document.getElementById('logo-preview');

        // Loop through selected files and display them
        Array.from(fileInput.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.classList.add('relative');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('w-1/4');

                const removeButton = document.createElement('button');
                removeButton.innerText = 'X';
                removeButton.classList.add('absolute', 'top-0', 'right-0', 'text-red-500');
                removeButton.onclick = function() {
                    div.remove(); // Remove the preview div
                };

                div.appendChild(img);
                div.appendChild(removeButton);
                logoPreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    // Mark an existing photo for removal
    function removeExistingPhoto(logo, index) {
        removedLogos.push(logo); // Add to the removed logos array
        document.getElementById(`existing-logo-${index}`).remove(); // Remove the preview
        document.getElementById('removed-logos').value = JSON.stringify(removedLogos); // Update hidden input
    }
</script>
