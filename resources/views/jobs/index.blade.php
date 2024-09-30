<x-layout>
    <div class="space-y-10">
        <section class="text-center pt-6">
            <h1 class="font-bold text-4xl">Выберите Параметры Поиска</h1>

            <x-forms.form action="/search" class="mt-6 flex justify-center items-center gap-5">
                <x-forms.input :label="false" name="q2" placeholder="Web Developer..." />
                <x-forms.select label="" name="schedule">
                    @php
                        $prikols = [1, 2, 3, 4, 5, 6, 7];
                    @endphp
                    @foreach ($prikols as $prikol)
                        <option>{{ $prikol }}</option>
                    @endforeach
                </x-forms.select>
                <x-forms.select label="" name="schedule">
                    @php
                        $prikols = [1, 2, 3, 4, 5, 6, 7];
                    @endphp
                    @foreach ($prikols as $prikol)
                        <option>{{ $prikol }}</option>
                    @endforeach
                </x-forms.select>
                <x-forms.button>Поиск</x-forms.button>
            </x-forms.form>
        </section>

        <section class="pt-10">
            <x-section-heading>Требуют Внимания</x-section-heading>

            <div class="grid lg:grid-cols-3 gap-8 mt-6">
                @foreach ($featuredJobs as $job)
                    <x-job-card :$job />
                @endforeach
            </div>
        </section>

        <section>
            <x-section-heading>Tags</x-section-heading>

            <div class="mt-6 space-x-1">
                @foreach ($tags as $tag)
                    <x-tag :$tag />
                @endforeach
            </div>
        </section>

        <section>
            <x-section-heading>Recent Jobs</x-section-heading>

            <div class="mt-6 space-y-6">
                @foreach ($jobs as $job)
                    <x-job-card-wide :$job />
                @endforeach
            </div>
        </section>
    </div>
</x-layout>
