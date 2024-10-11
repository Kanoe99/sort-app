@props(['label', 'name'])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if ($label)
        <x-forms.label :$name :$label />
    @endif

    <div>
        {{ $slot }}

        <x-forms.error :error="$errors->first($name)" />
    </div>
</div>
