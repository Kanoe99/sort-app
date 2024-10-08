{{-- @dd(['attention printers' => $aprinters, 'printers' => $printers]) --}}

<x-layout>
    <section class="flex justify-between gap-5 mt-10">

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


            <x-carousel-book :printers="$printers" />
        </div>

        <div class="w-1/3 space-y-6">

            <div class="!h-20">
                <x-forms.form action="/search" class="flex justify-center items-center gap-5 !mx-0 w-[100%]">
                    <x-forms.input :label="false" placeholder="Что ищете?" name='q'
                        class="border-white/10 w-full" />
                    <x-forms.button>Поиск</x-forms.button>
                </x-forms.form>
            </div>

            <x-fade :aprinters="$aprinters" />
        </div>
    </section>
</x-layout>
