<x-layout>
    <x-page-heading>Регистрация</x-page-heading>

    <x-forms.form method="POST" action="/register" enctype="multipart/form-data" class="space-y-6">
        <x-forms.input label="Имя" name="name" />
        <x-forms.input label="Почта" name="email" type="email" />
        <x-forms.input label="Пароль" name="password" type="password" />
        <x-forms.input label="Подтверждение пароля" name="password_confirmation" type="password" />

        <x-forms.divider />

        <x-forms.button class="w-full">Зарегистрироваться</x-forms.button>
        <x-forms.link-wrapper>Уже есть аккаунт?
            <x-forms.link href="/login">Войти</x-forms.link>
        </x-forms.link-wrapper>
    </x-forms.form>
</x-layout>
