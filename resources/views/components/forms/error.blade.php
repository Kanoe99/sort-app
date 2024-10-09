@props(['error' => false])

@if ($error)
    <p {{ $attributes->merge(['class' => 'text-sm text-red-500 mt-1']) }}>{{ $error }}</p>
@endif
