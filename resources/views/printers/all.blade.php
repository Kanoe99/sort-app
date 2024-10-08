<x-layout>
    <section class="space-y-6 mt-6">
        <x-section-heading>Весь список</x-section-heading>
        <div class="text-black">
            {{ $printers->links() }}
        </div>
        @foreach ($printers as $printer)
            <x-printer-card-wide :printer="$printer"></x-printer-card-wide>
        @endforeach
        <div>
            {{ $printers->links() }}
        </div>
    </section>
</x-layout>
