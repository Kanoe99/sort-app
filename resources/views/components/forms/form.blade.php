<form {{ $attributes(['class' => 'max-w-2xl mx-auto', 'method' => 'GET']) }} enctype="multipart/form-data">
    @if ($attributes->get('method', 'GET') !== 'GET')
        @csrf
        @method($attributes->get('method'))
    @endif

    {{ $slot }}
</form>
