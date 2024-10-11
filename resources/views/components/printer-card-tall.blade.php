@props(['printer'])

<div
    {{ $attributes->merge(['class' => 'gap-x-6 p-4 bg-white/5 rounded-xl border border-transparent hover:border-white border-2 group transition-colors duration-300']) }}>
    <a href="/printers/{{ $printer->id }}" class="">
        <div class="flex flex-col justify-between h-full">

            <div>
                <div class="flex justify-between gap-1">
                    <div class="px-2 py-1 bg-[#101010] border-white border rounded-md flex items-center">
                        {{ $printer->type }}
                    </div>
                    <div class="space-y-2">
                        <div class="px-2 py-1 bg-[#101010] border-white border rounded-md"> {{ $printer->model }} </div>
                        <div class="px-2 py-1 bg-[#101010] border-white border rounded-md"> {{ $printer->number }} </div>
                    </div>
                </div>
                @if ($printer->IP)
                    <div class="mt-3">
                        IP -
                        {{ $printer->IP }}
                    </div>
                @endif
                <div class="flex gap-2">
                    <div class="space-y-2 mt-3 w-1/2">
                        <div class="px-1 py-2 bg-[#090909] rounded-md"> Локация:
                            {{ $printer->location }}
                        </div>
                        <div class="px-1 py-2 bg-[#090909] rounded-md"> Статус:
                            {{ $printer->status }}
                        </div>
                    </div>
                    <div class="mt-3 w-1/2">
                        @if (empty(json_decode($printer->logo)))
                            <div class="rounded-lg w-full h-full p-1 px-3 border-2 border-dashed text-md text-center">
                                Место <br />
                                для <br />
                                фото
                            </div>
                        @else
                            @php
                                $logos = json_decode($printer->logo);
                            @endphp
                            <img src="{{ asset('storage/' . $logos[0]) }}" class="w-full rounded-lg" alt="">
                            {{-- @foreach (json_decode($printer->logo) as $logo)
                            <img src="{{ asset('storage/' . $printer->logo) }}" class="w-[42px] rounded-lg" alt="">
                        @endforeach --}}
                        @endif
                    </div>
                </div>
                <div class="mt-2">
                    Комментарий:
                    {{ $printer->comment }}
                </div>
                <div class="px-2 py-1 bg-[#101010] border border-white rounded-md mt-2 block w-fit">
                    Счётчик страниц:
                    <span class="text-blue-500"> {{ $printer->counter }}</span> <br />
                    <span class="">Изменён -
                        {{ $printer->counterDate }}</span>
                </div>
                @if ($printer->fixDate)
                    <div class="mt-2">
                        Дата последнего ремонта - {{ $printer->fixdate }}
                    </div>
                @endif
                <div class="mt-2">
                    Комментарий:
                    {{ $printer->comment }}
                </div>
            </div>
            <div class="w-[100%] flex gap-2 flex-wrap justify-start">
                @if (isset($printer->tags))
                    @foreach ($printer->tags as $tag)
                        <x-tag :$tag size="small" />
                    @endforeach
                @endif
            </div>


        </div>
    </a>
</div>
