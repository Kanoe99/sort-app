<x-layout>
    <x-page-heading>Вход</x-page-heading>

    <x-forms.form method="POST" action="/login" class="space-y-6">
        <x-forms.input label="Email" name="email" type="email" />
        <x-forms.input label="Password" name="password" type="password" />

        <x-forms.button class="w-full">Войти</x-forms.button>

        <x-forms.link-wrapper>
            Нет аккаунта?
            <x-forms.link href="/register">Регистрация</x-forms.link>
        </x-forms.link-wrapper>
    </x-forms.form>
</x-layout>
