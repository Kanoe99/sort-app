{{-- @dd(['attention printers' => $aprinters, 'printers' => $printers]) --}}

<x-layout>
    <section class="flex justify-between gap-5 mt-6">

        <div class="w-2/3 space-y-6">
            <section>
                @if ($tags->isEmpty())
                    <x-placeholder>
                        Тут нет тегов ಠ_ಠ
                    </x-placeholder>
                @else
                    <div class="!h-20">
                        <x-carousel>
                            @foreach ($tags as $tag)
                                <x-tag :$tag />
                            @endforeach
                        </x-carousel>
                    </div>
                @endif
            </section>


            <section class="mt-6 space-y-6">
                <x-section-heading>Последние добавленные</x-section-heading>
                @if ($printers->isEmpty())
                    <x-placeholder>
                        Тут нет принтеров <span class="ml-5">(╯°□°）╯︵ ┻━┻</span>
                    </x-placeholder>
                @endif

                @foreach ($printers as $printer)
                    <x-printer-card-wide :$printer />
                @endforeach

            </section>
        </div>

        <div class="w-1/3 space-y-6">

            <div class="!h-20">
                <x-forms.form action="/search" class="flex justify-center items-center gap-5 !mx-0 w-[100%]">
                    <x-forms.input :label="false" placeholder="Что ищете?" name='q'
                        class="border-white/10 w-full" />

                    {{-- <x-forms.select label="" name="model" id="model">
                <option value="">Все модели</option>
                @foreach ($printers as $printer)
                    <option value="{{ $printer->model }}">{{ $printer->model }}</option>
                @endforeach
            </x-forms.select>
    
            <x-forms.select label="" name="location" id="location">
                <option value="">Все локации</option>
                @foreach ($printers as $printer)
                    <option>tion value="{{ $printer->location }}">{{ $printer->location }}</option>
                @endforeach
            </x-forms.select> --}}

                    <x-forms.button>Поиск</x-forms.button>
                </x-forms.form>
            </div>

            <section class="space-y-6">
                <x-section-heading>Требуют Внимания</x-section-heading>
                <x-fade>
                    @if ($aprinters->isEmpty())
                        <x-placeholder>
                            Никакой принтер
                            не требует
                            внимания ༼ つ ◕_◕ ༽つ
                        </x-placeholder>
                    @endif

                    @foreach ($aprinters as $printer)
                        <x-printer-card :$printer />
                    @endforeach
                </x-fade>
            </section>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modelSelect = document.getElementById('model');
            const locationSelect = document.getElementById('location');

            // Create a mapping of models to locations
            const printerData = @json(
                $printers->groupBy('model')->map(function ($group) {
                    return $group->pluck('location')->unique();
                }));

            modelSelect.addEventListener('change', function() {
                const selectedModel = this.value;

                // Clear existing options in location select
                locationSelect.innerHTML = '<option value="">Все локации</option>';

                // If a model is selected, populate the location dropdown accordingly
                if (selectedModel && printerData[selectedModel]) {
                    printerData[selectedModel].forEach(location => {
                        const option = document.createElement('option');
                        option.value = location;
                        option.textContent = location;
                        locationSelect.appendChild(option);
                    });
                }
            });
        });
    </script>
</x-layout>
