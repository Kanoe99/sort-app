<x-layout>
    <div class="space-y-10">
        <section class="text-center pt-6">
            <h2 class="font-bold text-4xl">Выберите Параметры Поиска</h2>

            <x-forms.form action="/search" class="mt-6 flex justify-center items-center gap-5">
                <x-forms.input :label="false" placeholder="Что ищите?" name='q' />
                <x-forms.select label="" name="model">
                    <option value="">Все модели</option>
                    @foreach ($printers as $printer)
                        <option value="{{ $printer->model }}">{{ $printer->model }}</option>
                    @endforeach
                </x-forms.select>
                <x-forms.select label="" name="location">
                    <option value="">Все локации</option>
                    @foreach ($printers as $printer)
                        <option value="{{ $printer->location }}">{{ $printer->location }}</option>
                    @endforeach
                </x-forms.select>
                <x-forms.button>Поиск</x-forms.button>
            </x-forms.form>

        </section>

        <section class="pt-10">
            <x-section-heading>Требуют Внимания</x-section-heading>
            @if ($aprinters->isEmpty())
                <x-placeholder>
                    Никакой принтер
                    не требует
                    внимания ༼ つ ◕_◕ ༽つ
                </x-placeholder>
            @endif

            <div class="grid lg:grid-cols-3 gap-8 mt-6">

                @foreach ($aprinters as $printer)
                    <x-job-card :$printer />
                @endforeach
            </div>
        </section>

        <section>
            <x-section-heading>Теги</x-section-heading>

            @if ($tags->isEmpty())
                <x-placeholder>
                    Тут нет тегов ಠ_ಠ
                </x-placeholder>
            @endif

            <div class="mt-6 space-x-1">
                @foreach ($tags as $tag)
                    <x-tag :$tag />
                @endforeach
            </div>
        </section>

        <section>
            <x-section-heading>Последние добавленные</x-section-heading>

            @if ($printers->isEmpty())
                <x-placeholder>
                    Тут нет принтеров <span class="ml-5">(╯°□°）╯︵ ┻━┻</span>
                </x-placeholder>
            @endif

            <div class="mt-6 space-y-6">
                @foreach ($printers as $printer)
                    <x-job-card-wide :$printer />
                @endforeach
            </div>
        </section>
    </div>
</x-layout>
